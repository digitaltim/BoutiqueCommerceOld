<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\UI\Views;

use Slim\Container;

class ListView
{
    protected $container; // dependency injection container
    private $model;

    public function __construct(Container $container)
    {
        $this->container = $container;
        // Instantiate services/dependencies
        $container['db'];
        $container['view'];
        $container['mailer'];
    }

    public function __get($name)
    {
        return $this->container->{$name};
    }

    public function output($reqest, $response, $args)
    {
        $class = "It_All\\BoutiqueCommerce\\Models\\".ucwords($args['table']);
        $dbTableModel = new $class($this->db);
        $modelClass = "It_All\\BoutiqueCommerce\\Models\\Every".ucwords($args['table'])."List";
        $this->model = new $modelClass($dbTableModel);
        return $this->view->render($response, 'admin/list.twig', ['title' => $args['table'], 'results' => $this->model->getRecords()]);
    }
}
