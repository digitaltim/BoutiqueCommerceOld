<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Orders;

use It_All\BoutiqueCommerce\Src\Domain\Admin\Orders\Order\OrderModelFactory;
use It_All\BoutiqueCommerce\Src\Infrastructure\Database\DatabaseTableModel;
use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\QueryBuilder;
use It_All\BoutiqueCommerce\Src\Domain\Admin\Orders\Order\OrderModel;

class OrdersModel extends DatabaseTableModel
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

        $orderRows = []; // store records for one order to be passed to factory

        foreach ($queryResults as $row) {
            if ($previousOrderId != $row['id']) {
                $previousOrderId = $row['id'];
                if (count($orderRows) > 0) {
                    $orders[] = OrderModelFactory::create($orderRows);
                    $orderRows = [];
                }
            }
            $orderRows[] = $row;
        }

        return $orders;
    }


    public function getOrdersQueryResults()
    {
        $q = new QueryBuilder("
            SELECT
            orders.*,
            contacts.name AS customer,
            orders.contact_id AS customer_id,
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
