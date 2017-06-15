<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Orders\Order;

use It_All\BoutiqueCommerce\Src\Domain\Admin\Products\ProductModel;
use It_All\BoutiqueCommerce\Src\Domain\Admin\Customers\CustomerModel;

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
}
