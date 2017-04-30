<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Controllers;

use It_All\BoutiqueCommerce\UI\UiRsDbTable;
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
        return $this->view->render($response, 'admin/CRUD/insert.twig', ['title' => 'Insert '.$this->tableName, 'table' => $this->tableName]);
    }
}