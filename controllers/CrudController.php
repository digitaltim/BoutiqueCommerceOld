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

    public function postInsert($reqest, $response, $args)
    {
        $this->tableName = $args['table'];
        try {
            $this->setModel();
        } catch (\Exception $e) {
            return $this->view->render($response, 'admin/error.twig', ['title' => 'Error', 'message' => $e->getMessage()]);
        }
        if ($this->model->insert($_POST)) {
            echo 'insert success';
            return $response->withStatus(302)->withHeader('Location', '/CRUD/'.$this->tableName);
        }
        else {
            echo 'insert failure';
        }

        $form = $this->getInsertForm();
        return $this->view->render($response, 'admin/CRUD/insert.twig', ['title' => 'Post Insert '.$this->tableName, 'table' => $this->tableName, 'form' => $form->generate()]);
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