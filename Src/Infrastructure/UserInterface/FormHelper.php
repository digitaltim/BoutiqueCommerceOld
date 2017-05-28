<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure\UserInterface;

class FormHelper
{
    private static $fields;

    /**
     * for each field with a validation error, this adds the 'error' key and message and an error class attribute to fieldName
     */
    private static function insertErrors()
    {
        if (isset($_SESSION['validationErrors'])) {
            foreach ($_SESSION['validationErrors'] as $fieldName => $errorMessage) {
                if (array_key_exists($fieldName, self::$fields)) {
                    if (isset(self::$fields[$fieldName]['attributes']['class'])) {
                        self::$fields[$fieldName]['attributes']['class'] .= " formFieldError";
                    } else {
                        self::$fields[$fieldName]['attributes']['class'] = "formFieldError";
                    }
                    self::$fields[$fieldName]['error'] = $errorMessage;
                }
            }
            unset($_SESSION['validationErrors']);
        }
    }

    private static function insertValues(array $values)
    {
        foreach (self::$fields as $fieldName => $fieldInfo) {
            if (!((array_key_exists('persist', $fieldInfo)) &&
                  ($fieldInfo['persist'] === false)) &&
                (isset($values[$fieldName]))) {
                switch ($fieldInfo['tag']) {
                    case 'textarea':
                        self::$fields[$fieldName]['value'] = $values[$fieldName];
                        break;
                    case 'select':
                        self::$fields[$fieldName]['selected'] = $values[$fieldName];
                        break;
                    default:
                        self::$fields[$fieldName]['attributes']['value'] = $values[$fieldName];
                }
            }
        }
    }

    /**
     * @param array $fields
     * @param array|null $values (could be db record)
     * @return array
     * if values input is array use that to insert values, if not and values are in session, use that then unset it
     */
    public static function insertValuesErrors(array &$fields, array $values = null): array
    {
        self::$fields = $fields;
        if (is_array($values)) {
            self::insertValues($values);
        } elseif (isset($_SESSION['formInput']) && is_array($_SESSION['formInput'])) {
            self::insertValues($_SESSION['formInput']);
            unset($_SESSION['formInput']);
        }
        self::insertErrors();
        return self::$fields;
    }

    public static function getGeneralFormError()
    {
        $generalFormError = $_SESSION['generalFormError'] ?? '';
        unset($_SESSION['generalFormError']);
        return $generalFormError;
    }
}
