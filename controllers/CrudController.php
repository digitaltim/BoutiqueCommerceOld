<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Controllers;

use It_All\BoutiqueCommerce\Models\DbColumn;
use It_All\BoutiqueCommerce\UI\UiRsDbTable;
use It_All\FormFormer\Form;
use Slim\Container;

class CrudController extends Controller
{
    private $tableName;
    private $model;

    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    private function setModel()
    {
        $class = 'It_All\BoutiqueCommerce\Models\\'.ucfirst($this->tableName);
        try {
            $this->model = (class_exists($class)) ? new $class($this->tableName, $this->db) : new \It_All\BoutiqueCommerce\Models\DbTable($this->tableName, $this->db);
        } catch (\Exception $e) {
            throw new \Exception('Invalid Table Name: ' . $this->tableName);
        }
    }

    public function index($request, $response, $args)
    {
        $this->tableName = $args['table'];
        try {
            $this->setModel();
        } catch (\Exception $e) {
            return $this->view->render($response, 'admin/error.twig', ['title' => 'Error', 'message' => $e->getMessage()]);
        }
        $UiRsDbTable = new UiRsDBTable($this->model);
        $results = '';
        if ($res = $this->model->select('*')) {
            if ($this->model->isInsertAllowed()) {
                $results .= "<h3 style='display:inline;'><a href='".$this->router->pathFor('crud.getInsert', ['table' => $this->tableName])."'>Insert New</a></h3>";
            }
            $results .= (pg_num_rows($res) > 0) ? $UiRsDbTable->makeTable($res) : 'No results';
        }
        else {
            $results = "Query Error";
        }
        return $this->view->render($response, 'admin/CRUD/index.twig', ['title' => $this->tableName, 'results' => $results]);

    }

    public function show($request, $response, $args)
    {
        $this->tableName = $args['table'];
        try {
            $this->setModel();
        } catch (\Exception $e) {
            return $this->view->render($response, 'admin/error.twig', ['title' => 'Error', 'message' => $e->getMessage()]);
        }
        $UiRsDbTable = new UiRsDbTable($this->model);
        if ($res = $this->model->select('*', ['id' => $args['id']])) {
            $results = (pg_num_rows($res) > 0) ? $UiRsDbTable->makeTable($res) : 'No results';
        }
        else {
            $results = "Query Error";
        }
        return $this->view->render($response, 'admin/CRUD/show.twig', ['title' => $this->tableName, 'results' => $results]);

    }

    public function getInsert($request, $response, $args)
    {
        $this->tableName = $args['table'];
        // todo it seems this code does not work in the model. why can we render a view from another function? (not in the route?)
        try {
            $this->setModel();
        } catch (\Exception $e) {
            return $this->view->render($response, 'admin/error.twig', ['title' => 'Error', 'message' => $e->getMessage()]);
        }
        $form = $this->getInsertForm();
        return $this->view->render($response, 'admin/CRUD/insert.twig', ['title' => 'Insert '.$this->tableName, 'table' => $this->tableName, 'form' => $form->generate()]);
    }

    private function getInsertForm()
    {
        $insertFormAttributes = array(
            'method' => 'post',
            'action' => $_SERVER['SCRIPT_NAME']."?t=$this->tableName",
            'novalidate' => 'novalidate'
        );
        $insertForm = new Form($insertFormAttributes, 'verbose');
        $this->addFormFields('insert', $insertForm, 'sub');
        return $insertForm;
    }

    private function isPrimaryKeyColumnForInsert($column, $dbAction)
    {
        return $column->isPrimaryKey() && $dbAction == 'insert';
    }

    private function addFormFields($dbAction, $form, $submitFieldName, $fieldValues = null)
    {
        if ($dbAction != 'insert' && $dbAction != 'update') {
            trigger_error("invalid form type $dbAction", E_USER_ERROR); // will exit
        }
        $defaultColumnValues = $this->model->getDefaultColumnValues();
        $excludedColumns = $this->model->getExcludedColumns($dbAction);
        $disabledColumns = $this->model->getDisabledColumns($dbAction);
        $hiddenColumnValues = $this->model->getHiddenColumnValues($dbAction);

        foreach ($this->model->getColumns() as $column) {
            $columnName = $column->getName();
            if (!in_array($columnName, $excludedColumns) && !$this->isPrimaryKeyColumnForInsert($column, $dbAction) ) {
                // initial field value
                // $fieldValues argument takes precedence
                // if column not set in $fieldValues, set blank for update
                // and use default value for insert, table-level default from $defaultColumnValues
                // takes precedence of column->getDefaultValue
                if (is_array($fieldValues) && isset($fieldValues[$columnName])) {
                    $initialValue = $fieldValues[$columnName];
                }
                elseif ($dbAction == 'insert') {
                    if (array_key_exists($columnName, $defaultColumnValues)) {
                        $initialValue = $defaultColumnValues[$columnName];
                    }
                    else {
                        $initialValue = $column->getDefaultValue();
                    }
                }
                else {
                    $initialValue = '';
                }
                $isDisabled = (in_array($columnName, $disabledColumns)) ? true : false;
                $hiddenValue = (array_key_exists($columnName, $hiddenColumnValues)) ? $hiddenColumnValues[$columnName] : null;
                $this->addFormFieldFromDbColumn($form, $column, $initialValue, null, null, null, $isDisabled, $hiddenValue);
            }
        }
        $submitButtonInfo = array(
            'attributes' => array(
                'name' => $submitFieldName,
                'type' => 'submit',
                'value' => 'Go'
            )
        );
        $form->addField('input', $submitButtonInfo['attributes']);
    }

    private function addFormFieldFromDbColumn(Form $form, DbColumn $column, $initialValue = '', $label = null, $placeholder=null, $fieldName = null, $isDisabled = false, $hiddenValue = null)
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
            $label = ($label === null) ? ucwords(str_replace("_"," ",$columnName)) : $label;
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
                    throw new \Exception("Undefined field: $columnType ($columnName)", E_USER_ERROR); // exits
            } // switch
        }
        if ($isFieldgroup) {
//            var_dump($newField['customSettings']);die();
            $fieldFieldGroup = $form->addFieldGroup($newField['type'], $newField['name'], $newField['label'], '', $column->isRequired(), $newField['customSettings']);
        } else {
            if ($column->isRequired()) {
                $newField['attributes']['required'] = 'required';
            }
            $fieldFieldGroup = $form->addField($newField['tag'], $newField['attributes'], $newField['label'], '', $newField['customSettings']); // descriptor is blank
            if ($callSetValue) {
                $fieldFieldGroup->value($initialValue);
            }
        }
        if ($valErrMsg = $column->getValidationError()) {
            $form->setFfgError($fieldFieldGroup, $valErrMsg);
        }
        return $fieldFieldGroup;
    }
}