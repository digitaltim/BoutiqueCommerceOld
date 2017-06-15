<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Products;

class ProductModel
{
    private $name;
    private $styleNumber;
    private $quantity;
    private $price;
    private $status;
    private $productId;

    public function __construct(
        string $name,
        string $styleNumber,
        int $quantity,
        int $price,
        string $status,
        string $productId)
    {
        $this->name = $name;
        $this->styleNumber = $styleNumber;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->status = $status;
        $this->productId = $productId;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getStyleNumber()
    {
        return $this->styleNumber;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getProductId()
    {
        return $this->productId;
    }
}
