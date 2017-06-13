<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Orders;

use It_All\BoutiqueCommerce\Src\Infrastructure\AdminView;
use It_All\BoutiqueCommerce\Src\Infrastructure\UserInterface\FormHelper;
use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\QueryBuilder;

class OrdersView extends AdminView
{
    public function index($request, $response, $args)
    {
        $res = (new OrdersModel)->getOrders();

        $insertLink = ($this->authorization->check($this->container->settings['authorization']['orders.insert'])) ? ['text' => 'Insert Order', 'route' => 'orders.insert'] : false;

        return $this->view->render(
            $response,
            'admin/orders/orders.twig',
            [
                'title' => 'Orders',
                'primaryKeyColumn' => 'order_id',
                'insertLink' => false,
                'updateColumn' => 'order_id',
                'updatePermitted' => $this->authorization
                    ->check($this->container->settings['authorization']['orders.update']),
                'updateRoute' => 'orders.put.update',
                'addDeleteColumn' => false,
                'deleteRoute' => 'orders.delete',
                'table' => pg_fetch_all($res),
                'navigationItems' => $this->navigationItems
            ]
        );
    }
}
