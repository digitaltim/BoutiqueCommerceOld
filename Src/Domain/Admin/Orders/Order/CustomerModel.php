<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Orders\Order;

class CustomerModel
{
    private $name;
    private $id;

    public function __construct(
        string $name,
        string $id)
    {
        $this->name = $name;
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }
    public function getId() {
        return $this->id;
    }
}
