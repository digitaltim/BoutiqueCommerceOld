<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure\Utilities;

use It_All\FormFormer\Form;

function getBaseUrl()
{
    global $config;
    $baseUrl = "https://";
    if ($config['domainUseWww']) {
        $baseUrl .= "www.";
    }
    $baseUrl .= $_SERVER['SERVER_NAME'];
    return $baseUrl;
}

function getCurrentPage($includeQueryString = true, $includeIndex = true): string
{
    $page = (!$includeIndex && $_SERVER['SCRIPT_NAME'] == '/index.php') ? "/" : $_SERVER['SCRIPT_NAME'];
    if ($includeQueryString && isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
        $page .= "?" . $_SERVER['QUERY_STRING'];
    }
    return $page;
}

/**
 * @param string $toPage page and query string only.
 * @param bool $addAdminDir
 * if called with no args, redirects to current page with proper protocol, www or not based on config, and query string
 * suppress redirect if on dev server and there's a page error and we're in debug mode so any errors can be echoed
 */
function redirect($toPage = null, $addAdminDir = false)
{
    global $config;
    if (is_null($toPage)) {
        $toPage = getCurrentPage(true, false);
    }
    // add / if nec
    if (substr($toPage, 0, 1) != "/") {
        $toPage = "/" . $toPage;
    }
    if ($addAdminDir) {
        $toPage = "/" . $config['dirs']['admin'] . $toPage;
    }
    $to = getBaseUrl() . $toPage;
    header("Location: $to");
    exit();
}

/**
 * @param $to mixed array of emails or string of email
 * @param $subject string
 * @param $body string
 */
function emailNotify($to, string $subject, string $body)
{
    global $config;
    if ($config['sendNotificationEmails']) {
        $phpmailer = Mailer::getPhpmailer();
        if (is_array($to)) {
            foreach ($to as $email) {
                $phpmailer->addAddress($email);
            }
        } else {
            $phpmailer->addAddress($to);
        }
        $phpmailer->isHTML(false);
        $phpmailer->Subject = $subject;
        $phpmailer->Body = $body;
        if (!$phpmailer->send()) {
            trigger_error('email send failure: ' . $phpmailer->ErrorInfo);
            return false;
        }
        return true;
    }
    return false;
}

/**
 * protects array from xss by changing actual array values to escaped characters
 * @param array $arr
 */
function arrayProtectRecursive(array &$arr)
{
    foreach ($arr as $k => $v) {
        if (is_array($v)) {
            arrayProtectRecursive($arr[$k]);
        } else {
            $arr[$k] = protectXSS($v);
        }
    }
}

/**
 * use for inputs that are to be displayed in HTML
 * including values stored in the database
 * @param string $input
 * @return string
 */
function protectXSS(string $input)
{
    return htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * determines if a $sessionId id is valid.
 * @param $session_id
 * @param bool optional $isEmptyIdValid
 * @return bool
 */
function sessionValidId($sessionId, $isEmptyIdValid = true): bool
{
    if ($isEmptyIdValid && strlen($sessionId) == 0) { // if blank, there is no session id
        return true;
    }
    return preg_match('/^[-,a-zA-Z0-9]{1,128}$/', $sessionId) > 0;
}

/**
 * determines if current page is https
 * @return bool
 */
function isHttps(): bool
{
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || intval($_SERVER['SERVER_PORT']) === 443;
}

/**
 * determines if url host name begins with 'www'
 * @return bool
 */
function isWww(): bool
{
    return (strtolower(substr($_SERVER['SERVER_NAME'], 0, 3)) == 'www');
}

/**
 * Returns true if the current script is running from the command line (ie, CLI).
 */
function isRunningFromCommandLine(): bool
{
    return php_sapi_name() == 'cli';
}

/**
 * gets extension of a fileName
 */
function getFileExt(string $fileName)
{
    if (!strstr($fileName, '.')) {
        return false;
    }
    $fileNameParts = explode('.', $fileName);
    return $fileNameParts[count($fileNameParts) - 1];
}

/**
 * converts array to string
 * @param array $arr
 * @param int $level
 * @return string
 */
function arrayWalkToStringRecursive(array $arr, int $level = 0): string
{
    $out = "";
    $tabs = " ";
    for ($i = 0; $i < $level; $i++) {
        $tabs .= " ";
    }
    foreach ($arr as $k => $v) {
        // GLOBALS can be too big for memory and can cause an infinite loop GLOBALS: GLOBALS: ...
        if ($k != 'GLOBALS') {
            $out .= "$tabs$k: ";
            if (is_object($v)) {
                $out .= 'object type: '.get_class($v);
            } elseif (is_array($v)) {
                $newLevel = $level + 1;
                if ($newLevel > 10) {
                    $out .= ' array, too deep, quitting';
                } else {
                    $out .= arrayWalkToStringRecursive($v, $newLevel);
                }
            } else {
                $out .= (string)$v;
            }
        }
    }
    return $out;
}

function printPreArray(array $in, bool $die = false)
{
    echo "<pre>";
    print_r($in);
    echo "</pre>";
    if ($die) {
        die('Array print and die.');
    }
}

/**
 * @param $var_array
 * @return mixed
 * can be used to set multiple vars in one cookie
 */
function buildCookie($var_array)
{
    if (is_array($var_array)) {
        foreach ($var_array as $index => $data) {
            $out .= ($data != "") ? $index . "=" . $data . "|" : "";
        }
    }
    return rtrim($out, "|");
}

function breakCookie($cookie_string)
{
    $array = explode("|", $cookie_string);
    foreach ($array as $i => $stuff) {
        $stuff = explode("=", $stuff);
        $array[$stuff[0]] = $stuff[1];
        unset($array[$i]);
    }
    return $array;
}

/**
 * @param string $cookieName
 * https://www.owasp.org/index.php/PHP_Security_Cheat_Sheet
 */
function deleteCookie(string $cookieName)
{
    setcookie($cookieName, "", 1);
    setcookie($cookieName, false);
    unset($_COOKIE[$cookieName]);
}

/**
 * @param $d1
 * @param $d2 if null compare to today
 * d1, d2 already verified to be isDbDate()
 * @return int
 */
function dbDateCompare($d1, $d2 = null): int
{
    // inputs 2 dates (Y-m-d) and returns d1 - d2 in seconds
    if ($d2 === null) {
        $d2 = date('Y-m-d');
    }
    return convertDateMktime($d1) - convertDateMktime($d2);
}

/**
 * @param $dbDate already been verified to be isDbDate()
 * @return int
 */
function convertDateMktime($dbDate): int
{
    return mktime(0, 0, 0, substr($dbDate, 5, 2), substr($dbDate, 8, 2), substr($dbDate, 0, 4));
}

/**
 * @param string $number
 * if number is an integer with .00 it will be cropped and just the int returned. otherwise the original arg will be returned
 */
function crop00FromInt($check)
{
    return (substr($check, strlen($check) - 3, 3) == '.00') ? substr($check, 0, strlen($check) - 3) : $check;
}

function convertDbDateDbTimestamp($dbDate, $time = 'end')
{
    if ($time == 'end') {
        return "$dbDate 23:59:59.999999";
    } else {
        return "$dbDate 0:0:0";
    }
}

/**
 * Note also capable of creating a field group (radio buttons) for enum fields
 */
function getFormFieldFromDbColumn(Form $form, \It_All\BoutiqueCommerce\Src\Infrastructure\Database\DbColumnORM $column, $errorMessage = null, $initialValue = '', $label = null, $placeholder=null, $fieldName = null, $isDisabled = false, $hiddenValue = null)
{
    $defaultTextareaRows = 2;
    $defaultTextareaCols = 40;
    $maxTextFieldLength = 30; // size of text box, can have more characters entered if field allows
    $columnName = $column->getName();
    $columnType = $column->getType();
    $columnCharacterMaximumLength = $column->getCharacterMaximumLength();
    $columnUdtName = $column->getUdtName();
    $isFieldgroup = false; // default. set true for group fields
    // field setup
    $fieldName = ($fieldName == null) ? $columnName : $fieldName;
    $callSetValue = true; // will call $field->value($initialValue)
    if ($hiddenValue != null) {
        $callSetValue = false;
        $newField = [
            'tag' => 'input',
            'attributes' => [
                'type' => 'hidden',
                'name' =>$fieldName,
                'value' => $hiddenValue
            ]
        ];
    }
    else {
        $label = ($label === null) ? $columnName : $label;
        $newField = [
            'label' => $label,
            'tag' => 'input',
            'attributes' => [
                'name' => $fieldName
            ],
            'customSettings' => []
        ];
        // disabled
        if ($isDisabled) {
            $newField['attributes']['disabled'] = true;
        }
        if ($placeholder !== null) {
            $newField['attributes']['placeholder'] = $placeholder;
        }
        switch ($columnType) {
            case 'text':
                $newField['tag'] = 'textarea';
                $newField['attributes']['rows'] = $defaultTextareaRows;
                $newField['attributes']['cols'] = $defaultTextareaCols;
                break;
            case 'character':
                $newField['attributes']['type'] = 'text';
                $newField['attributes']['minlength'] = $columnCharacterMaximumLength;
                $newField['attributes']['maxlength'] = $columnCharacterMaximumLength;
                $newField['attributes']['size'] = $columnCharacterMaximumLength;
                break;
            case 'character varying':
                $newField['attributes']['type'] = 'text';
                $newField['attributes']['maxlength'] = $columnCharacterMaximumLength;
                $newField['attributes']['size'] = round($columnCharacterMaximumLength / 3, 0);
                if ($newField['attributes']['size'] > $maxTextFieldLength) {
                    $newField['attributes']['size'] = $maxTextFieldLength;
                }
                break;
            case 'timestamp without time zone':
            case 'timestamp with time zone':
                $newField['attributes']['type'] = 'text';
                $newField['attributes']['maxlength'] = 50;
                $newField['attributes']['size'] = 20;
                break;
            case 'smallint':
            case 'bigint':
            case 'integer':
                $newField['attributes']['type'] = 'number';
                break;
            case 'numeric':
                $newField['attributes']['type'] = 'number';
                break;
            case 'date':
                $newField['attributes']['type'] = 'date'; // making this a date field doesn't work.
                break;
            case 'boolean':
                $newField['attributes']['type'] = 'checkbox';
                $newField['checked'] = ($initialValue == 't') ? true : false;
                $newField['attributes']['value'] = 't'; // this doesn't make it checked, just sets the value to 't' (instead of 'on') if it is checked
                $callSetValue = false;
                break;
            case 'USER-DEFINED':
                unset($newField['attributes']['value']);
                // is this an enum field?
                $enumSql = "SELECT e.enumlabel as enum_value FROM pg_type t JOIN pg_enum e on t.oid = e.enumtypid JOIN pg_catalog.pg_namespace n ON n.oid = t.typnamespace WHERE t.typname = $1";
                $res = pg_query_params($enumSql, array($columnUdtName));
                switch (pg_num_rows($res)) {
                    case 0:
                        throw new \Exception("undefined USER-DEFINED field: $columnName");
                        break;
                    case 1:
                        throw new \Exception("enum field with only 1 enum value: $columnName");
                        break;
                    case 2:
                    case 3:
                    case 4:
                    case 5:
                        // if 2-5 values and required, make it a radio button field
                        unset($newField['attributes']['required']);
                        $isFieldgroup = true;
                        $choices = array();
                        while ($row = pg_fetch_assoc($res)) {
                            $choiceText = (strlen($row['enum_value']) == 0) ? '{N/A}' : $row['enum_value'];
                            $choices[$choiceText] = ($row['enum_value'] == $initialValue) ? [$row['enum_value'], true] : $row['enum_value'];
                        }
                        $newField['name'] = $columnName;
                        $newField['type'] = 'radio';
                        $newField['customSettings']['choices'] = $choices;
                        break;
                    default:
                        $newField['tag'] = 'select';
                        $selectOptions = array();
                        if ($column->getIsNullable()) {
                            $topText = ($placeholder !== null) ? $placeholder : "";
                            $selectOptions[''] = $topText;
                        }
                        while ($row = pg_fetch_assoc($res)) {
                            if (strlen($row['enum_value']) > 0) {
                                $selectOptions[$row['enum_value']] = $row['enum_value'];
                            }
                        }
                        $newField['customSettings']['selected_option_value'] = $initialValue;
                        $newField['customSettings']['options'] = $selectOptions;
                }
                break;
            default:
                throw new \Exception("Undefined field: $columnType ($columnName)");
        } // switch
    }
    if ($isFieldgroup) {
        $fieldFieldGroup = $form->addFieldGroup($newField['type'], $newField['name'], $newField['label'], '', $column->isRequired(), $newField['customSettings']);
    } else {
        if ($column->isRequired()) {
            $newField['attributes']['required'] = 'required';
        }
        $fieldFieldGroup = $form->addField($newField['tag'], $newField['attributes'], $newField['label'], '', $newField['customSettings']);
        if ($callSetValue) {
            $fieldFieldGroup->value($initialValue);
        }
    }
    if (strlen($errorMessage) > 0) {
        $form->setError($fieldFieldGroup, $errorMessage);
    }
    return $fieldFieldGroup;
}
