<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\UI\Views\Admin;

use Slim\Container;

class AdminView
{
    protected $container; // dependency injection container

    public function __construct(Container $container)
    {
        $this->container = $container;
        // Instantiate services/dependencies
        // todo is this necessary? (note duplication of base controller too)
        $container['db'];
        $container['view'];
        $container['mailer'];
    }

    public function __get($name)
    {
        return $this->container->{$name};
    }

}
