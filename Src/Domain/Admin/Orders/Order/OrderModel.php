<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Orders\Order;

use It_All\BoutiqueCommerce\Src\Domain\Admin\Marketing\AdCodes\AdCodesModel;
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
    private $datetime;
    private $type;
    private $notes;
    private $salesperson1;
    private $salesperson2;
    private $products;
    private $customer;
    private $amount;

    public function __construct(
        int $id,
        string $datetime,
        string $type,
        string $notes,
        string $salesperson1,
        string $salesperson2,
        array $products,
        CustomerModel $customerModel
    )
    {
        $this->id = $id;
        $this->datetime = $datetime;
        $this->type = $type;
        $this->notes = $notes;
//        $this->ship_amount = $ship_amount;
//        $this->ship_method = $ship_method;
//        $this->ship_name = $ship_name;
//        $this->ship_company = $ship_company;
//        $this->ship_address1 = $ship_address1;
//        $this->ship_address2 = $ship_address2;
//        $this->ship_city = $ship_city;
//        $this->ship_state = $ship_state;
//        $this->ship_country = $ship_country;
//        $this->ship_postal = $ship_postal;
//        $this->ship_phone = $ship_phone;
        $this->salesperson1 = $salesperson1;
        $this->salesperson2 = $salesperson2;
//        $this->checkoutperson = $checkoutperson;
//        $this->checkoutperson = $checkoutperson;
//        $this->checkoutperson = $checkoutperson;
//        $this->setAdCode($adCode);
        $this->setProducts($products);
        $this->setAmount();
        $this->customer = $customerModel;

    }

//    private function setAdCode(AdCodeModel $AdCodeModel)
//    {
//
//    }

    public function getId()
    {
        return $this->id;
    }

    public function getDate()
    {
        return substr($this->datetime, 0, 10);
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
        return $this->salesperson1 . ' ' . $this->salesperson2;
    }

    public function getProducts()
    {
        return $this->products;
    }

    private function addProduct(ProductModel $product)
    {
        $this->products[] = $product;
    }

    private function setProducts(array $products)
    {
        foreach ($products as $product) {
            $this->addProduct($product);
        }
    }

    private function setAmount()
    {
        foreach ($this->products as $product) {
            $this->amount += $product->getPrice();
        }
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setCustomer(CustomerModel $customerModel)
    {
        $this->customer = $customerModel;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public static function getOrder(string $index)
    {
        $previousOrderId = null;
        $orderModel = null;

        $orderProducts = [];

        $queryResults = self::getOrderQueryResults($index);

        // todo fix based on ois

        foreach ($queryResults as $row) {
            $orderProducts[] = new ProductModel(
                intval($row['product_id']),
                $row['item'],
                $row['style_number'],
                intval($row['quantity']),
                intval($row['price']),
                $row['status']
            );
        }

        // create new customer object
        $customerModel = new CustomerModel(intval($row['customer_id']), $row['customer']);

        return new OrderModel(
            intval($row['order_id']),
            $row['order date'],
            $row['type'],
            $row['notes'],
            $row['salesperson1'],
            $row['salesperson2'],
            $orderProducts,
            $customerModel
        );
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
            orders.salesperson1,
            orders.salesperson2,
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
