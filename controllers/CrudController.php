<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Controllers;

use It_All\BoutiqueCommerce\Models\DbColumn;
use It_All\BoutiqueCommerce\Models\DbTable;
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
            $this->model = (class_exists($class)) ? new $class($this->tableName, $this->db) : new DbTable($this->tableName, $this->db);
        } catch (\Exception $e) {
            // todo why doesn't this work? (even if $request, $response, $args are passed in)
            return $this->view->render($response, 'admin/error.twig', ['title' => 'Error', 'message' => 'model: Invalid Table Name: ' . $this->tableName]);
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

    public function getUpdate($request, $response, $args)
    {
        $this->tableName = $args['table'];
        $primaryKey = $args['primaryKey'];
        try {
            $this->setModel();
        } catch (\Exception $e) {
            return $this->view->render($response, 'admin/error.twig', ['title' => 'Error', 'message' => $e->getMessage()]);
        }
        $form = $this->getUpdateForm($primaryKey);
        return $this->view->render($response, 'admin/CRUD/insert.twig', ['title' => 'Update '.$this->tableName, 'table' => $this->tableName, 'form' => $form->generate()]);
    }

    public function getInsert($request, $response, $args)
    {
        $this->tableName = $args['table'];
        try {
            $this->setModel();
        } catch (\Exception $e) {
            return $this->view->render($response, 'admin/error.twig', ['title' => 'Error', 'message' => $e->getMessage()]);
        }
        $form = $this->getInsertForm();
        return $this->view->render($response, 'admin/CRUD/insert.twig', ['title' => 'Insert '.$this->tableName, 'table' => $this->tableName, 'form' => $form->generate()]);
    }

    public function postInsert($reqest, $response, $args)
    {
        $this->tableName = $args['table'];
        try {
            $this->setModel();
        } catch (\Exception $e) {
            return $this->view->render($response, 'admin/error.twig', ['title' => 'Error', 'message' => $e->getMessage()]);
        }
        if ($this->model->insert($reqest->getParsedBody())) {
            echo 'insert success';
            return $response->withStatus(302)->withHeader('Location', '/CRUD/'.$this->tableName);
        }
        else {
            echo 'insert failure';
        }

        $form = $this->getInsertForm();
        return $this->view->render($response, 'admin/CRUD/insert.twig', ['title' => 'Post Insert '.$this->tableName, 'table' => $this->tableName, 'form' => $form->generate()]);
    }

    public function postUpdate($reqest, $response, $args)
    {
        $this->tableName = $args['table'];
        $primaryKey = $args['primaryKey'];
        try {
            $this->setModel();
        } catch (\Exception $e) {
            return $this->view->render($response, 'admin/error.twig', ['title' => 'Error', 'message' => $e->getMessage()]);
        }
        if ($this->model->update($reqest->getParsedBody(), $primaryKey)) {
            echo 'update success';
            return $response->withStatus(302)->withHeader('Location', '/CRUD/'.$this->tableName);
        }
        else {
            echo 'update failure';
        }

        $form = $this->getUpdateForm($primaryKey);
        return $this->view->render($response, 'admin/CRUD/insert.twig', ['title' => 'Post Update '.$this->tableName, 'table' => $this->tableName, 'form' => $form->generate()]);
    }

    private function getInsertForm()
    {
        $insertFormAttributes = array(
            'method' => 'post',
            'action' => $this->container->get('router')->pathFor('crud.postInsert', ['table' => $this->tableName]),
            'novalidate' => 'novalidate'
        );
        $insertForm = new Form($insertFormAttributes, 'verbose');
        $this->addFormFields('insert', $insertForm, 'sub');
        return $insertForm;
    }

    private function getUpdateForm(string $primaryKey)
    {
        // get db record
        $rs = $this->model->select('*', [$this->model->getPrimaryKeyColumn() => $primaryKey]);
        $formAttributes = array(
            'method' => 'post',
            'action' => $this->container->get('router')->pathFor('crud.postUpdate', ['table' => $this->tableName, 'primaryKey' => $primaryKey]),
            'novalidate' => 'novalidate'
        );
        $form = new Form($formAttributes, 'verbose');

        $this->addFormFields('update', $form, 'sub', pg_fetch_array($rs));
        return $form;
    }

    public function delete($reqest, $response, $args)
    {
        $this->tableName = $args['table'];
        $primaryKey = $args['primaryKey'];
        try {
            $this->setModel();
        } catch (\Exception $e) {
            return $this->view->render($response, 'admin/error.twig', ['title' => 'Error', 'message' => $e->getMessage()]);
        }
        if ($this->model->delete($primaryKey)) {
            echo 'delete success';
//            return $response->withStatus(302)->withHeader('Location', '/CRUD/'.$this->tableName);
        }
        else {
            echo 'delete failure';
        }
    }

    private function getForm(string $type = 'insert')
    {

    }

    private function isPrimaryKeyColumnForInsert($column, $dbAction)
    {
        return $column->isPrimaryKey() && $dbAction == 'insert';
    }

    private function addFormFields($dbAction, $form, $submitFieldName, $fieldValues = null)
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
                \It_All\BoutiqueCommerce\Utilities\addFormFieldFieldGroupFromDbColumn($form, $column, $initialValue, null, null, null, $isDisabled, $hiddenValue);
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