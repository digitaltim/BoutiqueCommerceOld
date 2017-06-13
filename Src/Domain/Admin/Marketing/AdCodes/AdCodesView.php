<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Marketing\AdCodes;

use It_All\BoutiqueCommerce\Src\Infrastructure\AdminView;
use Slim\Container;

class AdCodesView extends AdminView
{
    public function __construct(Container $container)
    {
        $this->routePrefix = 'adCodes';
        $this->model = new AdCodesModel();

        parent::__construct($container);
    }
}
