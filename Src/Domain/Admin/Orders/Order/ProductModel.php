<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Orders\Order;

class ProductModel
{
    private $item;
    private $style_number;
    private $quantity;
    private $price;
    private $status;
    private $product_id;

    public function __construct(
        string $item,
        string $style_number,
        int $quantity,
        int $price,
        string $status,
        string $product_id)
    {
        $this->item = $item;
        $this->style_number = $style_number;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->status = $status;
        $this->product_id = $product_id;
    }

    public function getItem() {
        return $this->item;
    }
    public function getStyleNumber() {
        return $this->style_number;
    }
    public function getQuantity() {
        return $this->quantity;
    }
    public function getPrice() {
        return $this->price;
    }
    public function getStatus() {
        return $this->status;
    }
    public function getProductId() {
        return $this->product_id;
    }
}
