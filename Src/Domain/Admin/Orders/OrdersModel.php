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
        $this->roles = [
            'owner',
            'director',
            'manager',
            'shipper',
            'admin',
            'store',
            'bookkeeper'
        ];

        // Set select field options
        $this->rolesSelectFieldOptions = ['-- select --' => 'disabled'];
        foreach ($this->roles as $role) {
            $this->rolesSelectFieldOptions[$role] = $role;
        }
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

            'role' => [
                'tag' => 'select',
                'label' => 'Role',
                'validation' => ['required' => null],
                'attributes' => [
                    'id' => 'role',
                    'name' => 'role',
                    'type' => 'select',
                    'value' => ''
                ],
                'options' => $this->rolesSelectFieldOptions,
                'selected' => 'disabled'
            ],

            'password_hash' => [
                'tag' => 'input',
                'label' => 'Password',
                'validation' => ['minlength' => 12],
                'attributes' => [
                    'id' => 'password',
                    'type' => 'password',
                    'name' => 'password_hash',
                    'size' => '20',
                    'maxlength' => '30',
                ],
            ]
        ];
    }

    public function getFormFields(string $formType = 'insert', bool $persistPasswords = false): array
    {
        if ($formType != 'insert' && $formType != 'update') {
            throw new InvalidArgumentException("formType must be insert or update ".$formType);
        }

        $fields = array_merge($this->columns, [

            'confirm_password_hash' => [
                'tag' => 'input',
                'label' => 'Confirm Password',
                'validation' => ['minlength' => 12, 'confirm' => null],
                'attributes' => [
                    'type' => 'password',
                    'name' => 'confirm_password_hash',
                    'size' => '20',
                    'maxlength' => '30',
                ],
                'persist' => $persistPasswords,
            ],

            'submit' => FormHelper::getSubmitField()
        ]);

        $fields['password_hash']['persist'] = $persistPasswords;

        if ($formType == 'insert') {
            $fields['password_hash']['validation']['required'] = null;
            $fields['confirm_password_hash']['validation']['required'] = null;
        } else { // update
            $fields['password_hash']['label'] = 'Change Password (leave blank to keep existing password)';
            $fields['confirm_password_hash']['label'] = 'Confirm New Password';
            // override post method
            $fields['_METHOD'] = [
                'tag' => 'input',
                'attributes' => [
                    'type' => 'hidden',
                    'name' => '_METHOD',
                    'value' => 'PUT'
                ]
            ];
        }

        return $fields;
    }

    private function validateRole(string $roleCheck): bool
    {
        return in_array($roleCheck, $this->roles);
    }

//    If a null password is passed, the password field is not updated
    public function update(int $id, array $columnValues)
    {
        if (!$this->validateRole($columnValues['role'])) {
            throw new \Exception("Admin being updated with invalid role ".$columnValues['role']);
        }
        $q = new QueryBuilder("UPDATE orders SET name = $1, username = $2, role = $3", $columnValues['name'], $columnValues['username'], $columnValues['role']);
        $argNum = 4;
        if ($password !== null) {
            $q->add(", password_hash = $$argNum", password_hash($columnValues['password'], PASSWORD_DEFAULT));
            $argNum++;
        }
        $q->add(" WHERE id = $$argNum RETURNING username", $id);
        return $q->execute();
    }

    public function checkRecordExistsForUsername(string $username): bool
    {
        $q = new QueryBuilder("SELECT id FROM orders WHERE username = $1", $username);
        $res = $q->execute();
        return pg_num_rows($res) > 0;
    }

    public function selectForUsername(string $username)
    {
        $q = new QueryBuilder("SELECT * FROM orders WHERE username = $1", $username);
        return $q->execute();
    }

    // If a null password is passed, the password field is not checked
    public function hasRecordChanged(array $columValues, $primaryKeyValue, string $primaryKeyName = 'id', array $skipColumns = null, array $record = null): bool
    {
        if (strlen($columValues['password_hash']) == 0) {
            $skipColumns[] = 'password_hash';
        } else {
            $columValues['password_hash'] = password_hash($columValues['password_hash'], PASSWORD_DEFAULT);
        }

        return parent::hasRecordChanged($columValues, $primaryKeyValue, $primaryKeyName, $skipColumns);
    }

    public function getOrders()
    {
        // $q = new QueryBuilder("SELECT id, order_dt, store_receipt_number FROM orders WHERE order_dt >= '2017-03-02'");

        // $q = new QueryBuilder("SELECT a.*, b.name as ship_name, b.address1 as ship_address1, b.city as ship_city, b.state as ship_state, b.country as ship_country, b.postal as ship_postal, b.email as ship_email, b.phone as ship_phone, b.email_list as ship_email_list FROM orders a LEFT OUTER JOIN contacts b ON a.contact_id=b.id WHERE true AND a.order_dt >= '2017-03-02' ORDER BY a.order_dt DESC, a.id DESC");

        // $q = new QueryBuilder("
        //     SELECT
        //     ord.id as order_id,
        //     con.name,
        //     ord.order_dt,
        //     oi.id as oi_id,
        //     ois.id as ois_id,
        //     ois.order_item_status,
        //     ord.order_type,
        //     oi.item_name,
        //     oi.item_qty,
        //     oi.item_price
        //     FROM orders ord
        //     JOIN order_items oi
        //     ON ord.id = oi.order_id
        //     JOIN order_item_status ois
        //     ON oi.id = ois.order_item_id
        //     JOIN contacts con
        //     ON ord.contact_id = con.id
        //     order by order_dt
        //     desc limit 10
        // ");

        $q = new QueryBuilder("
            SELECT
            orders.order_dt AS \"order date\",
            orders.id AS order_id,
            orders.order_type AS type,
            contacts.name AS customer,
            orders.ship_amount AS amount,
            orders.notes AS notes,
            orders.salesperson1 AS salespeople,
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
            order by order_dt
            desc limit 10
        ");

        return $q->execute();
    }
}
