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
        $this->model = (class_exists($class)) ? new $class($this->tableName, $this->db) : new \It_All\BoutiqueCommerce\Models\DbTable($this->tableName, $this->db);

    }

    public function index($request, $response, $args)
    {
        $this->tableName = $args['table'];
        $this->setModel();
        $UiRsDbTable = new UiRsDbTable($this->model);
        if ($res = $this->model->select('*')) {
            $results = (pg_num_rows($res) > 0) ? $UiRsDbTable->makeTable($res) : 'No results';
        }
        else {
            $results = "Query Error";
        }
        return $this->view->render($response, 'CRUD/show.twig', ['title' => $this->tableName, 'results' => $results]);

    }

    public function show($request, $response, $args)
    {
        $this->tableName = $args['table'];
        $this->setModel();
        $UiRsDbTable = new UiRsDbTable($this->model);
        if ($res = $this->model->select('*', ['id' => $args['id']])) {
            $results = (pg_num_rows($res) > 0) ? $UiRsDbTable->makeTable($res) : 'No results';
        }
        else {
            $results = "Query Error";
        }
        return $this->view->render($response, 'CRUD/show.twig', ['title' => $this->tableName, 'results' => $results]);

    }
}