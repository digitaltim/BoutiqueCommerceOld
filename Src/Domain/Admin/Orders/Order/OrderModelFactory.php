<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Orders\Order;

use It_All\BoutiqueCommerce\Src\Domain\Admin\Customers\CustomerModel;
use It_All\BoutiqueCommerce\Src\Domain\Admin\Products\ProductModel;

class OrderModelFactory
{

    public static function create(array $orderRows)
    {
        $products = [];

        foreach ($orderRows as $row) {
            // create new product object
            $products[] = new ProductModel(
                intval($row['product_id']),
                $row['item'],
                $row['style_number'],
                intval($row['quantity']),
                intval($row['price']),
                $row['status']
            );
        }

        // create new customer object
        $customerModel = new CustomerModel(
            intval($row['customer_id']),
            $row['customer']
        );

        return new OrderModel(
            intval($row['id']),
            $row['order_dt'],
            $row['order_type'],
            $row['notes'],
            $row['salesperson1'],
            $row['salesperson2'],
            $products,
            $customerModel
        );
    }
}