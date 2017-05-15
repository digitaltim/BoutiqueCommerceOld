<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\UI\Views\Admin\CRUD;

use It_All\BoutiqueCommerce\Models\DbColumn;
use It_All\BoutiqueCommerce\Models\DbTable;
use It_All\BoutiqueCommerce\UI\UiRsDbTable;
use It_All\BoutiqueCommerce\UI\Views\Admin\AdminView;
use It_All\FormFormer\Form;

class CrudView extends AdminView
{
    private $model;

    private function setModel()
    {
        $class = 'It_All\BoutiqueCommerce\Models\\'.ucfirst($this->tableName);
        try {
            $this->model = (class_exists($class)) ? new $class($this->db) : new DbTable($this->tableName, $this->db);
        } catch (\Exception $e) {
//            return $this->view->render($response, 'admin/error.twig', ['title' => 'Error', 'message' => 'model: Invalid Table Name: ' . $this->tableName]);
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
        $UiRsDbTable = new UiRsDbTable($this->model);
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

    public function getUpdate($request, $response, $args)
    {
        $this->tableName = $args['table'];
        $primaryKey = $args['primaryKey'];
        try {
            $this->setModel();
        } catch (\Exception $e) {
            return $this->view->render($response, 'admin/error.twig', ['title' => 'Error', 'message' => $e->getMessage()]);
        }
        $rs = $this->model->select('*', [$this->model->getPrimaryKeyColumn() => $primaryKey]);
        $fieldValues = (null !== $request->getParsedBody()) ? $request->getParsedBody() : pg_fetch_array($rs);

        $form = $this->getForm('update', $primaryKey, $fieldValues, $this->newvalidator->getErrors());

        return $this->view->render($response, 'admin/CRUD/form.twig', ['title' => 'Update '.$this->tableName, 'form' => $form->generate()]);
    }

    public function getInsert($request, $response, $args)
    {
        $this->tableName = $args['table'];
        try {
            $this->setModel();
        } catch (\Exception $e) {
            return $this->view->render($response, 'admin/error.twig', ['title' => 'Error', 'message' => $e->getMessage()]);
        }

        $fieldValues = (null !== $request->getParsedBody()) ? $request->getParsedBody() : [];
        $form = $this->getForm('insert', null, $fieldValues, $this->newvalidator->getErrors());

        return $this->view->render($response, 'admin/CRUD/form.twig', ['title' => 'Insert '.$this->tableName, 'form' => $form->generate()]);
    }

    private function isPrimaryKeyColumnForInsert(DbColumn $column, string $dbAction)
    {
        return $column->isPrimaryKey() && $dbAction == 'insert';
    }

    private function getForm(string $dbAction = 'insert', string $primaryKey = null, array $fieldValues = [], array $fieldErrors = [])
    {
        $pathParms = ['table' => $this->tableName];
        if ($dbAction == 'update') {
            $namedRoute = 'crud.postUpdate';
            $pathParms['primaryKey'] = $primaryKey;
        } else {
            $dbAction = 'insert';
            $namedRoute = 'crud.postInsert';
        }
        $formAttributes = array(
            'method' => 'post',
            'action' => $this->container->get('router')->pathFor($namedRoute, $pathParms),
            'novalidate' => 'novalidate'
        );
        $form = new Form($formAttributes, 'verbose');

        $this->addFormFields($dbAction, $form, 'sub', $fieldValues, $fieldErrors);
        return $form;
    }

    private function addFormFields(string $dbAction, Form $form, string $submitFieldName = 'sub', array $fieldValues = [], array $fieldErrors = [])
    {
        if ($dbAction != 'insert' && $dbAction != 'update') {
            throw new \Exception("invalid form type $dbAction");
        }
        $defaultColumnValues = $this->model->getDefaultColumnValues();
        $excludedColumns = $this->model->getExcludedColumns($dbAction);
        $disabledColumns = $this->model->getDisabledColumns($dbAction);
        $hiddenColumnValues = $this->model->getHiddenColumnValues($dbAction);

        foreach ($this->model->getColumns() as $column) {
            $columnName = $column->getName();
//            if (isset($fieldErrors[$columnName])) {
//                $column->setValidationError($fieldErrors[$columnName][0]);
//            }
            if (!in_array($columnName, $excludedColumns) && !$this->isPrimaryKeyColumnForInsert($column, $dbAction) ) {
                // initial field value
                // $fieldValues argument takes precedence
                // if column not set in $fieldValues, set blank for update
                // and use default value for insert, table-level default from $defaultColumnValues
                // takes precedence of column->getDefaultValue
                if (isset($fieldValues[$columnName])) {
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
                $errorMessage = (array_key_exists($columnName, $fieldErrors)) ? $fieldErrors[$columnName] : '';
                $isDisabled = (in_array($columnName, $disabledColumns)) ? true : false;
                $hiddenValue = (array_key_exists($columnName, $hiddenColumnValues)) ? $hiddenColumnValues[$columnName] : null;
                \It_All\BoutiqueCommerce\Utilities\getFormFieldFromDbColumn($form, $column, $errorMessage, $initialValue, null, null, null, $isDisabled, $hiddenValue);
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
}
