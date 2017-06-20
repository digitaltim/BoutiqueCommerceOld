<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Products;

class ProductModel
{
    private $id;
    private $name;
    private $styleNumber;
    private $quantity;
    private $price;
    private $status;

    public function __construct(
        int $id,
        string $name,
        string $styleNumber,
        int $quantity,
        int $price,
        string $status)
    {
        $this->id = $id;
        $this->name = $name;
        $this->styleNumber = $styleNumber;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->status = $status;
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

    public function getId()
    {
        return $this->id;
    }
}
