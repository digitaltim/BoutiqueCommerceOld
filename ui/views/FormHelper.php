<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\UI\Views;

class FormHelper
{
    static private $fields;

    /**
     * @param array $fields
     * @return array
     * for each field with a validation error, this adds the 'error' key and message and an error class attribute to fieldName
     */
    static private function insertErrors()
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

    static private function insertValues(array $skipFields)
    {
        foreach (self::$fields as $fieldName => $fieldInfo) {
            if (!in_array($fieldName, $skipFields) && (isset($_SESSION['formInput'][$fieldName]))) {
                switch ($fieldInfo['tag']) {
                    case 'textarea':
                        self::$fields[$fieldName]['value'] = $_SESSION['formInput'][$fieldName];
                        break;
                    case 'select':
                        throw new \Exception('finish insertValues for select');
                        break;
                    default:
                        self::$fields[$fieldName]['attributes']['value'] = $_SESSION['formInput'][$fieldName];
                }
            }
        }
        unset($_SESSION['formInput']);
    }

    static public function insertValuesErrors(array &$fields, array $skipFieldsInsert): array
    {
        self::$fields = $fields;
        self::insertValues($skipFieldsInsert);
        self::insertErrors();
        return self::$fields;
    }

    static public function getGeneralFormError()
    {
        $generalFormError = $_SESSION['generalFormError'] ?? '';
        unset($_SESSION['generalFormError']);
        return $generalFormError;
    }
}
