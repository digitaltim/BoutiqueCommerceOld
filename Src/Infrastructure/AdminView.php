<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure;

use Slim\Container;

class AdminView
{
    protected $container; // dependency injection container
    protected $navigationItems;

    public function __construct(Container $container)
    {
        $this->container = $container;
        // Instantiate services/dependencies
        $container['db'];
        $container['view'];
        $container['mailer'];
        $container['flash'];

        // Instantiate navigation navbar contents
        $navAdmin = new NavAdmin($this->db);
        $this->navigationItems = $navAdmin->getSections();
    }

    public function __get($name)
    {
        return $this->container->{$name};
    }
}
