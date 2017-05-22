<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure;

class Middleware
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }
}
