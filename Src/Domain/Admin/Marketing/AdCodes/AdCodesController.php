<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Marketing\AdCodes;

use It_All\BoutiqueCommerce\Src\Infrastructure\CrudController;
use Slim\Container;

class AdCodesController extends CrudController
{
    public function __construct(Container $container)
    {
        $this->model = new AdCodesModel();
        $this->view = new AdCodesView($container);
        $this->routePrefix = 'adCodes';
        parent::__construct($container);
    }
}
