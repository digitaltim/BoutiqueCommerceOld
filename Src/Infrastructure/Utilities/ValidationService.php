<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure\Utilities;

/**
 * Class Validator
 * @package It_All\BoutiqueCommerce\Services
 * Inspired by https://github.com/cangelis/simple-validator
 */
class ValidationService
{
    private $errors;
    private $formInputData;

    public function __construct()
    {
        $this->errors = [];
    }

    public static function getRules(array $fields): array
    {
        $rules = [];
        foreach ($fields as $fieldName => $fieldInfo) {
            if (isset($fieldInfo['validation'])) {
                $rules[$fieldName] = $fieldInfo['validation'];
            }
        }

        return $rules;
    }

    private function isFieldRequired(string $fieldName, array $rules): bool
    {
        return array_key_exists('required', $rules[$fieldName]);
    }

    private function shouldProcessRule(
        string $fieldName,
        $fieldValue,
        string $rule,
        $ruleContext,
        array $rules
    ): bool
    {
        // if field is not required and value is empty stop validating this field (unless it is for a confirm rule)
//            if (!array_key_exists('confirm', $rules[$fieldName]) && !array_key_exists('required', $rules[$fieldName]) && self::isBlankOrNull($fieldValue)) {

        // if ruleContext is false do not process
        if ($ruleContext === false) {
            return false;
        }

        // if not a required field and has empty value do not process, unless this is a confirm rule
        if (!$this->isFieldRequired($fieldName, $rules) && self::isBlankOrNull($fieldValue) && $rule != 'confirm') {
            return false;
        }

        return true;
    }

    public function validate(array $formInputData, array $rules): bool
    {
        // save for special case where confirming two fields match, ex. password creation confirmation field
        $this->formInputData = $formInputData;

        foreach ($rules as $fieldName => $fieldRules) {

            // check for name of form field found in rules in submitted data set
            $fieldValue = isset($formInputData[$fieldName]) ? $formInputData[$fieldName] : '';

            foreach ($fieldRules as $rule => $ruleContext) {
                if ($this->shouldProcessRule($fieldName, $fieldValue, $rule, $ruleContext, $rules)) {
                    if (!$this->validateRule($fieldName, $fieldValue, $rule, $ruleContext)) {
                        break; // stop validating further rules for this field upon error
                    }
                }
            }
        }

        if (!empty($this->errors)) {
            $_SESSION['validationErrors'] = $this->errors;
            return false;
        }
        return true;
    }

    // note regex delimiter must be %.
    // note, do not use fieldName in error message, as the field label may be different and cause confusion
    private function validateRule(string $fieldName, string $fieldValue, string $rule, $context = null): bool
    {
        // special case, regex ie [a-z]
        if (substr($rule, 0, 1) == '%') {
            $regex = $rule;
            $rule = 'regex';
        }

        switch ($rule) {

            case 'enum':
                if (!is_array($context)) {
                    throw new \Exception("Context must be array for enum validation");
                }
                if (!in_array($fieldValue, $context)) {
                    $this->setError($fieldName, $rule, "Invalid option selected.");
                    return false;
                }
                break;

            case 'minlength':
                if (strlen($fieldValue) < $context) {
                    $this->setError($fieldName, $rule, "Must be $context characters or more");
                    return false;
                }
                break;

            case 'maxlength':
                if (strlen($fieldValue) > $context) {
                    $this->setError($fieldName, $rule, "Must be $context characters or less");
                    return false;
                }
                break;

            case 'regex':
                if (!filter_var(
                        $fieldValue,
                        FILTER_VALIDATE_REGEXP,
                        array(
                            "options"=>array("regexp" => "$regex")
                        )
                )) {
                    $this->setError($fieldName, $context);
                    return false;
                }
                break;

            case 'alphaspace':
                if (!filter_var(
                    $fieldValue,
                    FILTER_VALIDATE_REGEXP,
                    array(
                        "options"=>array("regexp" => "%^[a-zA-Z\s]+$%")
                    )
                )) {
                    $this->setError($fieldName, $rule, 'Only letters and spaces allowed');
                    return false;
                }
                break;

            case 'required':
                if (self::isBlankOrNull($fieldValue)) {
                    $this->setError($fieldName, $rule, $rule);
                    return false;
                }
                break;

            case 'numeric':
                if (!is_numeric($fieldValue)) {
                    $this->setError($fieldName, $rule);
                    return false;
                }
                break;

            case 'integer':
                if (!self::isInteger($fieldValue)) {
                    $this->setError($fieldName, $rule);
                    return false;
                }
                break;

            case 'date':
                if (!self::isDbDate($fieldValue)) {
                    $this->setError($fieldName, $rule);
                    return false;
                }
                break;

            case 'timestamp':
                if (!self::isDbTimestamp($fieldValue)) {
                    $this->setError($fieldName, $rule);
                    return false;
                }
                break;

            case 'confirm':
                // if there's already an error on the confirm field then do not validate the confirmation
                // convention prepends 'confirm_' to the second field for confirmation... remove to find other field to compare with

                $fieldNameToConfirm = trim($fieldName, 'confirm_');
                if (isset($this->errors[$fieldNameToConfirm])) {
                    break;
                }
                $fieldValueToConfirm = $this->formInputData[$fieldNameToConfirm];

                if ($fieldValue !== $fieldValueToConfirm) {
                    $confirmErrorMessage = 'must match';
                    $this->setError($fieldNameToConfirm, $rule, $confirmErrorMessage);
                    $this->setError($fieldName, $rule, $confirmErrorMessage);
                    return false;
                }
                break;

            default:
                throw new \Exception("Undefined rule $rule");
        }
        return true;
    }

    private function setError(string $fieldName, string $errorType, string $customMessage = null)
    {
        $this->errors[$fieldName] = (!is_null($customMessage)) ? $customMessage : "Must be $errorType";
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getError(string $fieldName)
    {
        return (array_key_exists($fieldName, $this->errors)) ? $this->errors[$fieldName] : '';
    }


    // VALIDATION FUNCTIONS

    /**
     * Check input for being an integer
     * either type int or the string equivalent of an integer
     * @param $in any type
     * note empty string returns false
     * note 0 or "0" returns true (as it should - no 0 problem as is mentioned by some sites)
     * note 4.00 returns true but "4.00" returns false
     * @return bool
     */
    public static function isInteger($check): bool
    {
        return (filter_var($check, FILTER_VALIDATE_INT) === false) ? false : true;
    }

    public static function isWholeNumber($check): bool
    {
        return (!self::isInteger($check) || $check < 0) ? false : true;
    }

    /**
     * checks if string is blank or null
     * this can be helpful for validating required form fields
     * @param string $check
     * @param bool $trim
     * @return bool
     */
    public static function isBlankOrNull($check, bool $trim = true): bool
    {
        if ($trim) {
            $check = trim($check);
        }
        return (strlen($check) == 0 || $check === null);
    }

    /**
     * checks if string is blank or zero
     * this can be helpful for validating numeric/integer form fields
     * @param string $check
     * @return bool
     */
    public static function isBlankOrZero(string $check, bool $trim = true): bool
    {
        if ($trim) {
            $check = trim($check);
        }
        return (strlen($check) == 0 || $check === '0');
    }

    /**
     * checks if string is a positive integer
     * @param string $check
     * @return bool
     */
    public static function isPositiveInteger(string $check): bool
    {
        return (self::isInteger($check) && $check > 0);
    }


    public static function isNumericPositive($check): bool
    {
        if (!is_numeric($check) || $check <= 0) {
            return false;
        }
        return true;
    }

    /**
     * @param string $check
     * @return bool
     * format YYYY-mm-dd
     */
    public static function isDbDate(string $check): bool
    {
        if (strlen($check) != 10) {
            return false;
        }
        if (substr($check, 4, 1) != "-" || substr($check, 7, 1) != "-") {
            return false;
        }
        // if all zeros not ok
        if ($check == '0000-00-00') {
            return false;
        }
        $yr = substr($check, 0, 4);
        $mo = substr($check, 5, 2);
        $dy = substr($check, 8, 2);
        if (substr($yr, 0, 2) != '20') {
            return false;
        }
        if ($mo > 12 || !is_numeric($mo) || (substr($mo, 0, 1) != '0' && substr($mo, 0, 1) != '1')) {
            return false;
        }
        if ($dy > 31 || !is_numeric($dy) || (substr($mo, 0, 1) != '0' && substr($mo, 0, 1) != '1')) {
            return false;
        }
        return true;
    }

    /**
     * @param $dbDate has already been verified to be isDbDate()
     * @return bool
     */
    public static function isDbDateInPast(string $dbDate):bool
    {
        return self::dbDateCompare($dbDate) < 0;
    }

    public static function isDigit($check)
    {
        if (strlen($check) != 1 || !self::isInteger($check)) {
            return false;
        }
        return true;
    }

    public static function isTwoCharNumber($check, $max = 99, $leadingZeroOk = true): bool
    {
        if (strlen($check) != 2) {
            return false;
        }
        if (!self::isDigit(substr($check, 0, 1)) || !self::isDigit(substr($check, 1))) {
            return false;
        }
        if (!$leadingZeroOk && substr($check, 0, 1) == '0') {
            return false;
        }
        $checkInt = (int)$check;
        if ($checkInt > $max) {
            return false;
        }
        return true;
    }

    public static function isDbMilitaryHours($check): bool
    {
        // 00 - 23
        return self::isTwoCharNumber($check, 23);
    }

    public static function isMinutes($check): bool
    {
        // 00 - 59
        return self::isTwoCharNumber($check, 59);
    }

    public static function isSeconds($check): bool
    {
        // 00 - 59
        return self::isMinutes($check);
    }

    /**
     * @param $d1
     * @param $d2 if null compare to today
     * d1, d2 already verified to be isDbDate()
     * @return int
     */
    public static function dbDateCompare($d1, $d2 = null): int
    {
        // inputs 2 mysql dates and returns d1 - d2 in seconds
        if ($d2 === null) {
            $d2 = date('Y-m-d');
        }
        return convertDateMktime($d1) - convertDateMktime($d2);
    }

    /**
     * @param $dbDate already been verified to be isDbDate()
     * @return int
     */
    public static function convertDateMktime($dbDate): int
    {
        return mktime(0, 0, 0, substr($dbDate, 5, 2), substr($dbDate, 8, 2), substr($dbDate, 0, 4));
    }

    public static function isDbTimestamp($check): bool
    {
        if (!self::isDbDate(substr($check, 0, 10))) {
            return false;
        }
        // remainder of string like  10:08:16.717238
        if (substr($check, 10, 1) != ' ') {
            return false;
        }
        $timeParts = explode(":", substr($check, 11));
        // ok without seconds
        if (count($timeParts) != 2 && count($timeParts) != 3) {
            return false;
        }
        foreach ($timeParts as $index => $timePart) {
            if ($index == 0) {
                if (!self::isDbMilitaryHours($timePart)) {
                    return false;
                }
            } elseif ($index == 1) {
                if (!self::isMinutes($timePart)) {
                    return false;
                }
            } else {
                if (!self::isSeconds(substr($timePart, 0, 2))) {
                    return false;
                }
                if (strlen($timePart) > 2 && !is_numeric(substr($timePart, 2))) {
                    return false;
                }
            }
        }
        return true;
    }

    public static function isEmail(string $check): bool
    {
        return filter_var($check, FILTER_VALIDATE_EMAIL);
    }

    // END VALIDATION FUNCTIONS
}
