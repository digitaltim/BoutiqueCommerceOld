<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Admins;

use It_All\BoutiqueCommerce\Src\Infrastructure\AdminCrudView;
use Slim\Container;

class AdminsView extends AdminCrudView
{
    public function __construct(Container $container)
    {
        parent::__construct($container, new AdminsModel(), 'admins');
    }

    /**
     * override to eliminate some columns
     * @param $request
     * @param $response
     * @param $args
     */
    public function index($request, $response, $args)
    {
        $this->indexView($response, 'id, name, username, role');
    }
}
