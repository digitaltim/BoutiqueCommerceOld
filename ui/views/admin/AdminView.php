<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\UI\Views\Admin;

use It_All\BoutiqueCommerce\UI\NavAdmin;
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

        // Instantiate navigation navbar contents
        $navAdmin = new NavAdmin($this->db);
        $this->navigationItems = $navAdmin->getSections();
    }

    public function __get($name)
    {
        return $this->container->{$name};
    }

}
