<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure;

use It_All\BoutiqueCommerce\Src\Infrastructure\Database\DatabaseTableModel;
use Slim\Container;

abstract class Controller
{
    protected $container; // dependency injection container
    protected $model;
    protected $view;
    protected $routePrefix;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function __get($name)
    {
        return $this->container->{$name};
    }
}
