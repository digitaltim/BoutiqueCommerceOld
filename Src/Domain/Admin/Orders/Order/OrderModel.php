<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Orders\Order;

use It_All\BoutiqueCommerce\Src\Domain\Admin\Products\ProductModel;
use It_All\BoutiqueCommerce\Src\Domain\Admin\Customers\CustomerModel;
use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\QueryBuilder;

class OrderModel
{
    /* Issues:
        1. Shipping in multiple shipments
        2. Restocking fees
        3. Tax
        4. Gift certificates
    */
    private $id;
    private $date;
    private $type;
    private $notes;
    private $salespeople; // TODO: separate out eventually?
    private $products;
    private $customer;
    private $amount;

    public function __construct(
        int $id,
        string $date,
        string $type,
        string $notes,
        string $salespeople)
    {
        $this->id = $id;
        $this->date = $date;
        $this->type = $type;
        $this->notes = $notes;
        $this->salespeople = $salespeople;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function getSalespeople()
    {
        return $this->salespeople;
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function addProductToOrder(
        $item,
        $style_number,
        $quantity,
        $price,
        $status,
        $product_id)
    {
        $productModel = new ProductModel(
            $item,
            $style_number,
            $quantity,
            $price,
            $status,
            $product_id
        );

        $this->products[] = $productModel;
    }

    public function setAmount()
    {
        foreach ($this->products as $product) {
            $this->amount += $product->getPrice();
        }
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function addCustomerToOrder(
        $name,
        $id)
    {
        $customerModel = new customerModel(
            $name,
            $id
        );

        $this->customer = $customerModel;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public static function getOrder(string $index)
    {
        $orders = []; // create orders array
        $previousOrderId = null;
        $orderModel = null;

        $queryResults = self::getOrderQueryResults($index);
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


    public static function getOrderQueryResults(string $index)
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
            WHERE order_id = $index
        ");

        return pg_fetch_all($q->execute());
    }
}
