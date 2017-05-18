<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Services;

use function It_All\BoutiqueCommerce\Utilities\printPreArray;

class Validator
{
    private $errors;

    public function __construct()
    {
        $this->errors = [];
    }

    public function validate(array $input, array $rules): bool
    {
        foreach ($input as $fieldName => $fieldValue) {
            if (array_key_exists($fieldName, $rules)) {
                foreach ($rules[$fieldName] as $rule) {
                    // if field is not required and value is empty stop validating
                    if (!in_array('required', $rules[$fieldName]) && self::isBlankOrNull($fieldValue)) {
                        break;
                    }
                    if (!$this->validateRule($fieldName, $fieldValue, $rule)) {
                        break; // stop validating further rules upon error
                    }
                }
            }
        }

        return empty($this->errors);
    }

    private function validateRule(string $fieldName, string $fieldValue, string $rule): bool
    {
        switch ($rule) {

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

    public function getErrorByFieldName(string $fieldName)
    {
        return (array_key_exists($fieldName, $this->errors)) ? $this->errors[$fieldName] : null;
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
    static public function isInteger($check): bool
    {
        return (filter_var($check, FILTER_VALIDATE_INT) === false) ? false : true;
    }

    static public function isWholeNumber($check): bool
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
    static public function isBlankOrNull($check, bool $trim = true): bool
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
    static public function isBlankOrZero(string $check, bool $trim = true): bool
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
    static public function isPositiveInteger(string $check): bool
    {
        return (self::isInteger($check) && $check > 0);
    }


    static public function isNumericPositive($check): bool
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
    static public function isDbDate(string $check): bool
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
    static public function isDbDateInPast(string $dbDate):bool
    {
        return self::dbDateCompare($dbDate) < 0;
    }

    static public function isDigit($check)
    {
        if (strlen($check) != 1 || !self::isInteger($check)) {
            return false;
        }
        return true;
    }

    static public function isTwoCharNumber($check, $max = 99, $leadingZeroOk = true): bool
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

    static public function isDbMilitaryHours($check): bool
    {
        // 00 - 23
        return self::isTwoCharNumber($check, 23);
    }

    static public function isMinutes($check): bool
    {
        // 00 - 59
        return self::isTwoCharNumber($check, 59);
    }

    static public function isSeconds($check): bool
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
    static public function dbDateCompare($d1, $d2 = null): int
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
    static public function convertDateMktime($dbDate): int
    {
        return mktime(0, 0, 0, substr($dbDate, 5, 2), substr($dbDate, 8, 2), substr($dbDate, 0, 4));
    }

    static public function isDbTimestamp($check): bool
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

    static public function isEmail($check)
    {
        return filter_var($check, FILTER_VALIDATE_EMAIL);
    }

    // END VALIDATION FUNCTIONS

}
