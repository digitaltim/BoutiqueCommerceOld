<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure\UserInterface;

class FormHelper
{
    private static $fields;

    /**
     * @param array $fields
     * @return array
     * for each field with a validation error, this adds the 'error' key and message and an error class attribute to fieldName
     */
    private static function insertErrors()
    {
        if (isset($_SESSION['validationErrors'])) {
            foreach ($_SESSION['validationErrors'] as $fieldName => $errorMessage) {
                if (array_key_exists($fieldName, self::$fields)) {
                    if (isset($fields[$fieldName]['attributes']['class'])) {
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

    private static function insertValues()
    {
        foreach (self::$fields as $fieldName => $fieldInfo) {
            if (!((array_key_exists('persist', $fieldInfo)) &&
                  ($fieldInfo['persist'] === false)) &&
                (isset($_SESSION['formInput'][$fieldName]))) {
                switch ($fieldInfo['tag']) {
                    case 'textarea':
                        self::$fields[$fieldName]['value'] = $_SESSION['formInput'][$fieldName];
                        break;
                    case 'select':
                        self::$fields[$fieldName]['selected'] = $_SESSION['formInput'][$fieldName];
                        break;
                    default:
                        self::$fields[$fieldName]['attributes']['value'] = $_SESSION['formInput'][$fieldName];
                }
            }
        }
        unset($_SESSION['formInput']);
    }

    public static function insertValuesErrors(array &$fields): array
    {
        self::$fields = $fields;
        self::insertValues();
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
