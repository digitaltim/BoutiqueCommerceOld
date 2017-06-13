<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Orders;

use It_All\BoutiqueCommerce\Src\Infrastructure\Model;
use It_All\BoutiqueCommerce\Src\Infrastructure\UserInterface\FormHelper;
use function It_All\BoutiqueCommerce\Src\Infrastructure\Utilities\printPreArray;
use It_All\BoutiqueCommerce\Src\Infrastructure\Utilities\ValidationService;
use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\QueryBuilder;
use Psr\Log\InvalidArgumentException;

class OrdersModel extends Model
{
    private $roles;
    private $rolesSelectFieldOptions;

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

    public function getFormFields(string $formType = 'insert', bool $persistPasswords = false): array
    {
        if ($formType != 'insert' && $formType != 'update') {
            throw new InvalidArgumentException("formType must be insert or update ".$formType);
        }

        $fields = array_merge($this->columns, [

            'submit' => FormHelper::getSubmitField()
        ]);

        $fields['password_hash']['persist'] = $persistPasswords;

        // override post method
        $fields['_METHOD'] = [
            'tag' => 'input',
            'attributes' => [
                'type' => 'hidden',
                'name' => '_METHOD',
                'value' => 'PUT'
            ]
        ];

        return $fields;
    }

    public function getOrders()
    {
        $q = new QueryBuilder("
            SELECT
            orders.order_dt AS \"order date\",
            orders.id AS order_id,
            orders.order_type AS type,
            contacts.name AS customer,
            orders.ship_amount AS amount,
            orders.notes AS notes,
            orders.salesperson1 || ' ' || orders.salesperson2 AS salespeople,
            order_item_status.id AS ois_id,
            order_items.item_name AS item,
            inventory_items.style_number AS style_number,
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

        return $q->execute();
    }
}
