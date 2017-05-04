<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Middleware;

/**
*
*/
class Middleware
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }
}