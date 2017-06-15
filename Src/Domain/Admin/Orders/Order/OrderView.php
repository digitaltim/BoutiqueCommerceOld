<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Orders\Order;

use It_All\BoutiqueCommerce\Src\Domain\Admin\Orders\Order\OrderModel;
use It_All\BoutiqueCommerce\Src\Infrastructure\AdminView;
use Slim\Container;

class OrderView extends AdminView
{
    public function __construct(Container $container)
    {
        $this->routePrefix = 'orders';
        // $this->model = new OrderModel();

        parent::__construct($container);
    }

    public function show($request, $response, $args)
    {
        $orders = OrderModel::getOrder($args['primaryKey']);

        return $this->view->render(
            $response,
            'admin/orders/orders_objects.twig',
            [
                'title' => 'Orders',
                'primaryKeyColumn' => 'order_id',
                'table' => $orders,
                'navigationItems' => $this->navigationItems
            ]
        );
    }
}
