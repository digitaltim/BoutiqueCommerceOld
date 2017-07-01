<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Orders;

use It_All\BoutiqueCommerce\Src\Infrastructure\AdminView;
use Slim\Container;

class OrdersView extends AdminView
{
    public function __construct(Container $container)
    {
        $this->routePrefix = 'orders';
        $this->model = new OrdersModel();

        parent::__construct($container);
    }

    public function index($request, $response, $args)
    {
        $orders = $this->model->getOrders();


        return $this->view->render(
            $response,
            'admin/orders/orders.twig',
            [
                'title' => 'Orders',
                'primaryKeyColumn' => 'id',
                'table' => $orders,
                'navigationItems' => $this->navigationItems
            ]
        );
    }
}
