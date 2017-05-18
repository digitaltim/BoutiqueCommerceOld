<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Controllers;

use It_All\BoutiqueCommerce\UI\NavAdmin;
use It_All\BoutiqueCommerce\Models\DbColumn;
use It_All\BoutiqueCommerce\UI\Views\Admin\CRUD\CrudHelper;
use It_All\BoutiqueCommerce\UI\Views\Admin\CRUD\CrudView;
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

    /**
     * @param $request
     * @return bool
     *  $rules = [
     *      'fieldname' => [
     *          'rule1',
     *          'rule2'
     *      ], ...
     *  ];
     */
    private function validateFieldInput($request): bool
    {
        // look for fields that are required but are not in the posted array (ie radio button or checkbox fields for which no value has been checked). if found, enter them as empty string to fieldInputs so they are marked invalid. note, skip primary key field as it should not be validated.
        $fieldInputs = $request->getParsedBody();
        foreach ($this->model->getColumns() as $c) {
            $cName = $c->getName();
            if ($c->isRequired() && !$this->model->isPrimaryKeyColumn($cName) && !array_key_exists($cName, $fieldInputs)) {
                $fieldInputs[$cName] = '';
            }
        }

        // get field rules from db columns
        $rules = [];
        foreach ($fieldInputs as $fieldName => $postedValue) {
            // find db columns
            if ($c = $this->model->getColumnByName($fieldName)) {
                $rules[$fieldName] = $this->setDbColumnValidationRules($c);
            }
            // ignore any non-db columns
        }

        return $this->newvalidator->validate($fieldInputs, $rules);
    }

    public function postInsert($request, $response, $args)
    {
        $this->tableName = $args['table'];
        $this->model = CrudHelper::getModel($this->tableName, $this->db);
        $redisplayForm = false;
        if ($this->validateFieldInput($request)) {
            try {
                    $this->model->insert($request->getParsedBody());
                } catch (\Throwable $e) {
                    $redisplayForm = true;
            }
        } else {
            $redisplayForm = true;
        }

        if ($redisplayForm) {
            // redisplay the form with input values and error(s)
            $cv = new CrudView($this->container);
            return $cv->getInsert($request, $response, $args);
        }

        $this->flash->addMessage('success', 'Inserted');
        return $response->withRedirect($this->router->pathFor('crud.show', ['table' => $this->tableName]));

    }

    public function postUpdate($request, $response, $args)
    {
        $this->tableName = $args['table'];
        $this->model = CrudHelper::getModel($this->tableName, $this->db);

        $primaryKey = $args['primaryKey'];

        $redisplayForm = false;
        $generalErrorMessage = null;
        if ($this->validateFieldInput($request)) {
            try {
                $this->model->update($request->getParsedBody(), $primaryKey);
            } catch (\Throwable $e) {
                $redisplayForm = true;
                $generalErrorMessage = $e->getMessage();
            }
        } else {
            $redisplayForm = true;
        }

        if ($redisplayForm) {
            // redisplay the form with input values and error(s)
            $cv = new CrudView($this->container);
            return $cv->getUpdate($request, $response, $args, $generalErrorMessage);
        }

        $this->flash->addMessage('success', 'Updated');
        return $response->withRedirect($this->router->pathFor('crud.show', ['table' => $this->tableName]));
    }

    public function delete($request, $response, $args)
    {
        $this->tableName = $args['table'];
        $this->model = CrudHelper::getModel($this->tableName, $this->db);

        $primaryKey = $args['primaryKey'];
        try {
            $this->model->delete($primaryKey);
        } catch (\Throwable $e) {
            $this->flash->addMessage('failure', 'Not Deleted '.$e->getMessage());
        }

        $this->flash->addMessage('success', 'Deleted');
        return $response->withRedirect($this->router->pathFor('crud.show', ['table' => $this->tableName]));
    }
}
