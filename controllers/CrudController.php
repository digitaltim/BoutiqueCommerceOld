<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Controllers;

use It_All\BoutiqueCommerce\UI\NavAdmin;
use It_All\BoutiqueCommerce\Models\DbColumn;
use It_All\BoutiqueCommerce\Models\DbTable;
use It_All\BoutiqueCommerce\UI\Views\Admin\CRUD\CrudView;
use It_All\FormFormer\Form;
use Slim\Container;

class CrudController extends Controller
{
    private $tableName;
    private $model;
    protected $navigationItems;

    public function __construct(Container $container)
    {
        parent::__construct($container);

        // Instantiate navigation navbar contents
        $navAdmin = new NavAdmin($this->db);
        $this->navigationItems = $navAdmin->getSections();
    }

    private function setModel()
    {
        $class = 'It_All\BoutiqueCommerce\Models\\'.ucfirst($this->tableName);
        try {
            $this->model = (class_exists($class)) ? new $class($this->db) : new DbTable($this->tableName, $this->db);
        } catch (\Exception $e) {
            // todo why doesn't this work? (even if $request, $response, $args are passed in)
//            return $this->view->render($response, 'admin/error.twig', ['title' => 'Error', 'message' => 'model: Invalid Table Name: ' . $this->tableName]);
            throw new \Exception('Invalid Table Name: ' . $this->tableName);
        }
    }

    private function setDbColumnValidationRules(DbColumn $c): array
    {
        $columnRules = [];
        if ($c->isRequired()) {
            $columnRules[] = 'required';
        }

        switch ($c->getType()) {
            case 'numeric':
                $columnRules[] = 'numeric';
                break;
            case 'smallint':
            case 'bigint':
            case 'integer':
                $columnRules[] = 'integer';
                break;
            case 'date':
                $columnRules[] = 'date';
                break;
            case 'timestamp without time zone':
                $columnRules[] = 'timestamp';
                break;
            case 'boolean':
            case 'character':
            case 'character varying':
            case 'text' :
            case 'USER-DEFINED':
                break; // no validation
            default:
                throw new \Exception("$this->type column type validation not defined, column $this->name");
        }

        return $columnRules;
    }

    private function validateFieldInput($request): bool
    {
//        $rules = [
//            'text' => [
//                'required',
//                'numeric'
//            ]
//        ];
        // get field rules from db columns
        $rules = [];
        foreach ($request->getParsedBody() as $fieldName => $postedValue) {
            // find db columns
            if ($c = $this->model->getColumnByName($fieldName)) {
                $rules[$fieldName] = $this->setDbColumnValidationRules($c);
            }
            // ignore non-db columns
        }
        return $this->newvalidator->validate($request->getParsedBody(), $rules);
    }

    public function postInsert($request, $response, $args)
    {
        $this->tableName = $args['table'];
        try {
            $this->setModel();
        } catch (\Exception $e) {
            return $this->view->render($response, 'admin/error.twig', [
                'title' => 'Error',
                'message' => $e->getMessage(),
                'navigationItems' => $this->navigationItems
            ]);
        }

        if ($this->validateFieldInput($request)) {
            try {
                $this->model->insert($request->getParsedBody());
                    //todo, render view, display success message
                    return $response->withStatus(302)->withHeader('Location', '/CRUD/'.$this->tableName);
                } catch (\Exception $e) {
                    // todo, render proper error page
                    die('query failure: '.$e->getMessage());
            }
        } else {
            // redisplay the form with input values and error(s)
            $cv = new CrudView($this->container);
            return $cv->getInsert($request, $response, $args);
        }
    }

    public function postUpdate($request, $response, $args)
    {
        $this->tableName = $args['table'];
        $primaryKey = $args['primaryKey'];
        try {
            $this->setModel();
        } catch (\Exception $e) {
            return $this->view->render($response, 'admin/error.twig', [
                'title' => 'Error',
                'message' => $e->getMessage(),
                'navigationItems' => $this->navigationItems
            ]);
        }

        // todo try catch to find conditions like No changes made
        if ($this->validateFieldInput($request)) {
            try {
                $this->model->update($request->getParsedBody(), $primaryKey);
                //todo, render view, display success message
                return $response->withStatus(302)->withHeader('Location', '/CRUD/'.$this->tableName);
            } catch (\Exception $e) {
                // todo, render proper error page
                die('query failure: '.$e->getMessage());
            }
        } else {
            // redisplay the form with input values and error(s)
            $cv = new CrudView($this->container);
            return $cv->getUpdate($request, $response, $args);
        }
    }

    public function delete($request, $response, $args)
    {
        $this->tableName = $args['table'];
        $primaryKey = $args['primaryKey'];
        try {
            $this->setModel();
        } catch (\Exception $e) {
            return $this->view->render($response, 'admin/error.twig', [
                'title' => 'Error',
                'message' => $e->getMessage(),
                'navigationItems' => $this->navigationItems
            ]);
        }
        if ($this->model->delete($primaryKey)) {
            echo 'delete success';
            // todo return a view w/ message
//            return $response->withStatus(302)->withHeader('Location', '/CRUD/'.$this->tableName);
        }
        else {
            echo 'delete failure';
        }
    }

    private function isPrimaryKeyColumnForInsert($column, $dbAction)
    {
        return $column->isPrimaryKey() && $dbAction == 'insert';
    }

    private function getForm(string $dbAction = 'insert', string $primaryKey = null, array $fieldValues = [])
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

        $this->addFormFields($dbAction, $form, 'sub', $fieldValues);
        return $form;
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