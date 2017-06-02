<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admins;

use It_All\BoutiqueCommerce\Src\Infrastructure\Utilities\ValidationService;
use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\QueryBuilder;
use Psr\Log\InvalidArgumentException;

class AdminsModel
{
    public function getFormFields(string $formType = 'insert'): array
    {
        if ($formType != 'insert' && $formType != 'update') {
            throw new InvalidArgumentException("formType must be insert or update ".$formType);
        }

        if ($formType == 'insert') {
            $passwordLabel = 'Password';
            $passwordValidation = ['required'];
            $passwordConfirmLabel = 'Confirm Password';
            $passwordConfirmValidation = ['required', 'confirm'];
        } else {
            $passwordLabel = 'Change Password (leave blank to keep existing password)';
            $passwordValidation = [];
            $passwordConfirmLabel = 'Confirm New Password';
            $passwordConfirmValidation = ['confirm'];
        }

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
                'label' => $passwordLabel,
                'validation' => $passwordValidation,
                'attributes' => [
                    'id' => 'password',
                    'type' => 'password',
                    'name' => 'password',
                    'size' => '15',
                    'maxlength' => '100',
                ],
                'persist' => false,
            ],

            'confirm_password' => [
                'tag' => 'input',
                'label' => $passwordConfirmLabel,
                'validation' => $passwordConfirmValidation,
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

    public function getValidationRules($formType = 'insert'): array
    {
        if ($formType != 'insert' && $formType != 'update') {
            throw new InvalidArgumentException("formType must be insert or update ".$formType);
        }

        return ValidationService::getRules($this->getFormFields($formType));
    }

    public function select(string $columns = '*')
    {
        $q = new QueryBuilder("SELECT $columns FROM admins");
        return $q->execute();
    }

    public function insert(string $name, string $username, string $password, string $role)
    {
        $q = new QueryBuilder("INSERT INTO admins (name, username, password_hash, role) VALUES($1, $2, $3, $4)", $name, $username, password_hash($password, PASSWORD_DEFAULT), $role);
        return $q->execute();
    }

    /**
     * If a null password is passed, the password field is not updated
     * @param string $name
     * @param string $username
     * @param string $password
     * @param string $role
     * @param int $id
     * @return \It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\recordset
     */
    public function update(int $id, string $name, string $username, string $role, string $password = null)
    {
        $q = new QueryBuilder("UPDATE admins SET name = $1, username = $2, role = $3", $name, $username, $role);
        $argNum = 4;
        if ($password !== null) {
            $q->add(", password_hash = $$argNum", password_hash($password, PASSWORD_DEFAULT));
            $argNum++;
        }
        $q->add(" WHERE id = $$argNum", $id);
//        $q = new QueryBuilder("UPDATE admins SET name = $1, username = $2, password_hash = $3, role = $4 WHERE id = $5", $name, $username, password_hash($password, PASSWORD_DEFAULT), $role, $id);
        return $q->execute();
    }

    public function delete(int $id)
    {
        $q = new QueryBuilder("DELETE FROM admins WHERE id = $1", $id);
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

    /**
     * If a null password is passed, the password field is not checked
     * @param int $id
     * @param string $name
     * @param string $username
     * @param string $role
     * @param string|null $password
     * @return bool
     */
    public function recordChanged(int $id, string $name, string $username, string $role, string $password = null): bool
    {
        $record = $this->selectForId($id);

        if ($name != $record['name'] || $username != $record['username'] || $role != $record['role']) {
            return true;
        }
        if ($password !== null && password_hash($password, PASSWORD_DEFAULT) != $record['password']) {
            return true;
        }

        return false;
    }
}
