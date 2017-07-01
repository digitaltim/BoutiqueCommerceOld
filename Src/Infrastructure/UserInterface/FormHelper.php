<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure\UserInterface;

use It_All\BoutiqueCommerce\Src\Infrastructure\Database\DatabaseColumnModel;

class FormHelper
{
    private static $fields;
    private static $focusField;
    const TEXTAREA_COLS = 50;
    const TEXTAREA_ROWS = 5;

    /**
     * for each field with a validation error, this adds the 'error' key and message and an error class attribute to fieldName
     */
    private static function insertErrors()
    {
        if (isset($_SESSION['validationErrors'])) {
            $eCount = 0;
            foreach ($_SESSION['validationErrors'] as $fieldName => $errorMessage) {
                $eCount++;
                if ($eCount == 1) {
                    // sets to the first error field
                    self::$focusField = $fieldName;
                }
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

            if (isset($values[$fieldName]) && (
                (!array_key_exists('persist', $fieldInfo) || $fieldInfo['persist']) ) ) {

                switch ($fieldInfo['tag']) {
                    case 'textarea':
                        self::$fields[$fieldName]['value'] = $values[$fieldName];
                        break;
                    case 'select':
                        // leave unchanged if no value was submitted so top (disabled) option remains selected by default
                        if (strlen($values[$fieldName]) > 0) {
                            self::$fields[$fieldName]['selected'] = $values[$fieldName];
                        }
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
     * if values input is array use that to insert values, if not and values are in session (ie form was submitted), use that then unset the session var
     * also sets the focus field to either the first field (if no errors), or the first field with an error
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

        self::setFocusField();
        self::insertErrors();
        return self::$fields;
    }

    // sets to the first field
    private static function setFocusField()
    {
        foreach (self::$fields as $fieldName => $fieldInfo) {
            self::$focusField = $fieldName;
            break;
        }
    }

    public static function getGeneralFormError()
    {
        $generalFormError = $_SESSION['generalFormError'] ?? '';
        unset($_SESSION['generalFormError']);
        return $generalFormError;
    }

    public static function getFocusField()
    {
        if (isset(self::$focusField)) {
            return self::$focusField;
        }
        return '';
    }

    public static function getSubmitField(string $value = 'Go!', string $name = 'submit')
    {
        return [
            'tag' => 'input',
            'attributes' => [
                'type' => 'submit',
                'name' => $name,
                'value' => $value
            ]
        ];
    }

    public static function getPutMethodField()
    {
        return [
            'tag' => 'input',
            'attributes' => [
                'type' => 'hidden',
                'name' => '_METHOD',
                'value' => 'PUT'
            ]
        ];
    }

    public static function getFieldFromDatabaseColumn(
        DatabaseColumnModel $column,
        string $labelOverride = null,
        string $inputTypeOverride = null,
        array $validationOverride = null,
        string $nameOverride = null,
        string $idOverride = null,
        bool $persist = true
    ): array
    {
        $columnName = $column->getName();
        $columnDefaultValue = $column->getDefaultValue();

        $formField = [
            'label' => ($labelOverride) ?: ucwords(str_replace('_', ' ', $columnName)),
            'attributes' => [
                'name' => ($nameOverride) ? $nameOverride : $columnName,
                'id' => ($idOverride) ? $idOverride : $columnName
            ],
            'validation' => ($validationOverride) ? $validationOverride : $column->getValidation()
        ];

        // the rest of $formField is derived in the switch statement
        switch ($column->getType()) {
            case 'text':
                $formField['tag'] = 'textarea';
                $formField['attributes']['cols'] = self::TEXTAREA_COLS;
                $formField['attributes']['rows'] = self::TEXTAREA_ROWS;
                break;

            // input fields of various types

            case 'date':
                $formField['tag'] = 'input';
                $formField['attributes']['type'] = 'date';
                break;


            case 'character varying':
                $formField['tag'] = 'input';
                $formField['attributes']['type'] = self::getInputType($inputTypeOverride);
                // must have max defined
                $formField['attributes']['maxlength'] = $column->getCharacterMaximumLength();
                $formField['validation']['maxlength'] = $column->getCharacterMaximumLength();
                if (isset($columnInfo['min'])) {
                    $formField['validation']['minlength'] = $columnInfo['min'];
                }
                break;

            case 'USER-DEFINED':
                $formField['tag'] = 'select';
                $enumOptions = $column->getEnumOptions();
                if (count($enumOptions) == 0) {
                    throw new \Exception("Options array must be set for enum field: ".$columnName);
                }
                $formField['validation']['enum'] = $enumOptions;
                $formField['options']['-- select --'] = 'disabled';
                foreach ($enumOptions as $option) {
                    $formField['options'][$option] = $option;
                }
                // set initial value to column default if it exists else to top option (-- select --)
                $formField['selected'] = ($columnDefaultValue != null && strlen($columnDefaultValue) > 0) ? $columnDefaultValue : 'disabled';
                
                break;

            default:
                $formField['tag'] = 'input';
                $formField['attributes']['type'] = self::getInputType($inputTypeOverride);
        }

        $formField['persist'] = $persist;

        return $formField;
    }

    private static function getInputType(string $inputTypeOverride = null)
    {
        return ($inputTypeOverride) ? $inputTypeOverride : 'text';
    }
}
