<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admins;

use It_All\BoutiqueCommerce\Src\Infrastructure\Utilities\ValidationService;
use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\QueryBuilder;

class AdminsModel
{
    public function getFormFields(): array
    {
        return [

            'name' => [
                'tag' => 'input',
                'label' => 'Name',
                'validation' => ['required'],
                'attributes' => [
                    'id' => 'name',
                    'name' => 'name',
                    'type' => 'text',
                    'size' => '15',
                    'maxlength' => '100',
                    'value' => ''
                ]
            ],

            'username' => [
                'tag' => 'input',
                'label' => 'Username',
                'validation' => ['required'],
                'attributes' => [
                    'id' => 'username',
                    'name' => 'username',
                    'type' => 'text',
                    'size' => '15',
                    'maxlength' => '100',
                    'value' => ''
                ]
            ],

            'role' => [
                'tag' => 'select',
                'label' => 'Role',
                'validation' => ['required'],
                'attributes' => [
                    'id' => 'role',
                    'name' => 'role',
                    'type' => 'select',
                    'value' => ''
                ],
                'options' => [
                    '-- select --' => 'disabled',
                    'owner' => 'owner',
                    'director' => 'director',
                    'manager' => 'manager',
                    'shipper' => 'shipper',
                    'admin' => 'admin',
                    'store' => 'store',
                    'bookkeeper' => 'bookkeeper'
                ],
                'selected' => 'disabled'
            ],

            'password' => [
                'tag' => 'input',
                'label' => 'Password',
                'validation' => ['required'],
                'attributes' => [
                    'type' => 'password',
                    'name' => 'password',
                    'size' => '15',
                    'maxlength' => '100',
                ],
                'persist' => false,
            ],

            'confirm_password' => [
                'tag' => 'input',
                'label' => 'Confirm Password',
                'validation' => ['required', 'confirm'],
                'attributes' => [
                    'type' => 'password',
                    'name' => 'confirm_password',
                    'size' => '15',
                    'maxlength' => '100',
                ],
                'persist' => false,
            ],

            'submit' => [
                'tag' => 'input',
                'attributes' => [
                    'type' => 'submit',
                    'name' => 'submit',
                    'value' => 'Go!'
                ]
            ]
        ];
    }

    public function getValidationRules(): array
    {
        return ValidationService::getRules($this->getFormFields());
    }

    public function select(string $columns = '*')
    {
        $q = new QueryBuilder("SELECT $columns FROM admins");
        return $q->execute();
    }

    public function getMinimumPermissionsInsert()
    {
        return $this->minimumPermissionsInsert;
    }

    public function insert(string $name, string $username, string $password, string $role)
    {
        $q = new QueryBuilder("INSERT INTO admins (name, username, password_hash, role) VALUES($1, $2, $3, $4)", $name, $username, password_hash($password, PASSWORD_DEFAULT), $role);
        return $q->execute();
    }

    public function update(string $name, string $username, string $password, string $role, int $id)
    {
        $q = new QueryBuilder("UPDATE admins SET name = $1, username = $2, password_hash = $3, role = $4 WHERE id = $5", $name, $username, password_hash($password, PASSWORD_DEFAULT), $role, $id);
        return $q->execute();
    }

    public function checkRecordExistsForUsername(string $username)
    {
        $q = new QueryBuilder("SELECT id FROM admins WHERE username = $1", $username);
        $q->execute();
        return $q->checkRecordsExist();
    }

    public function selectForUsername(string $username)
    {
        $q = new QueryBuilder("SELECT * FROM admins WHERE username = $1", $username);
        return $q->execute();
    }

    public function selectForId(int $id)
    {
        $q = new QueryBuilder("SELECT * FROM admins WHERE id = $1", $id);
        $res = $q->execute();
        return pg_fetch_assoc($res);
    }

    public function findAll() {
        $rows = [];
        $rs = $this->select();
        while ($row = pg_fetch_assoc($rs)) {
            $rows[] = $row;
        }
        return $rows;
    }
}
