<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Orders;

use It_All\BoutiqueCommerce\Src\Infrastructure\Model;
use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\QueryBuilder;
use It_All\BoutiqueCommerce\Src\Domain\Admin\Orders\Order\OrderModel;

class OrdersModel extends Model
{
    public function __construct()
    {
        parent::__construct('orders');
    }

    protected function setColumns()
    {
        $this->columns = [

            'order_dt' => [
                'tag' => 'input',
                'label' => 'Order Date',
                'validation' => [
                    'required' => null
                ],
                'attributes' => [
                    'id' => 'order_dt',
                    'name' => 'order_dt',
                    'type' => 'text',
                    'size' => '15',
                    'maxlength' => '20',
                    'value' => ''
                ]
            ],

            'order_type' => [
                'tag' => 'input',
                'label' => 'Order Type',
                'validation' => [
                    'required' => null,
                    'alphaspace' => null,
                    'maxlength' => 50
                ],
                'attributes' => [
                    'id' => 'order_type',
                    'name' => 'order_type',
                    'type' => 'text',
                    'size' => '15',
                    'maxlength' => '50',
                    'value' => ''
                ]
            ],
        ];
    }

    public function getOrders()
    {
        $orders = []; // create orders array
        $previousOrderId = null;
        $orderModel = null;

        $queryResults = $this->getOrdersQueryResults();
        foreach ($queryResults as $row) {
            // create new order object
            if ($previousOrderId != $row['order_id']) {
                $previousOrderId = $row['order_id'];
                $orderModel = new OrderModel(
                    intval($row['order_id']),
                    $row['order date'],
                    $row['type'],
                    $row['notes'],
                    $row['salespeople']
                );
                $orders[] = $orderModel;
            }

            // create new product object
            $orderModel->addProductToOrder(
                $row['item'],
                $row['style_number'],
                intval($row['quantity']),
                intval($row['price']),
                $row['status'],
                $row['product_id']
            );

            // create new customer object
            $orderModel->addCustomerToOrder(
                $row['customer'],
                $row['customer_id']
            );
        }

        // Create total amount for each order
        foreach ($orders as $order) {
            $order->setAmount();
        }

        return $orders;
    }


    public function getOrdersQueryResults()
    {
        $q = new QueryBuilder("
            SELECT
            orders.order_dt AS \"order date\",
            orders.id AS order_id,
            orders.order_type AS type,
            contacts.name AS customer,
            orders.contact_id AS customer_id,
            orders.ship_amount AS amount,
            orders.notes AS notes,
            orders.salesperson1 || ' ' || orders.salesperson2 AS salespeople,
            order_item_status.id AS ois_id,
            order_items.item_name AS item,
            inventory_items.style_number AS style_number,
            inventory_items.id AS product_id,
            order_items.item_qty AS quantity,
            order_items.item_price AS price,
            order_item_status.order_item_status AS status
            FROM orders
            JOIN order_items
            ON orders.id = order_items.order_id
            JOIN order_item_status
            ON order_items.id = order_item_status.order_item_id
            JOIN contacts
            ON orders.contact_id = contacts.id
            JOIN inventory_items
            ON order_items.item_id = inventory_items.id
            WHERE order_dt >= '2017-03-02'
            ORDER BY order_dt
            DESC
        ");

        return pg_fetch_all($q->execute());
    }
}
