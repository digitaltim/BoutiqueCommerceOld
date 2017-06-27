<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Admins;

use It_All\BoutiqueCommerce\Src\Infrastructure\Database\DatabaseTableModel;
use It_All\BoutiqueCommerce\Src\Infrastructure\UserInterface\FormHelper;
use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\QueryBuilder;

class AdminsModel extends DatabaseTableModel
{
    private $roles;

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

        parent::__construct('admins');
    }

    protected function setColumnsOld()
    {
        $this->columns = [

            'id' => [
                'type' => 'bigint',
                'validation' => ['required' => true]
            ],

            'username' => [
                'type' => 'character varying',
                'max' => 20,
                'validation' => ['required' => true, '%^[a-zA-Z]+$%' => 'only letters']
            ],

            'name' => [
                'type' => 'character varying',
                'max' => 50,
                'validation' => ['required' => true, 'alphaspace' => true]
            ],

            'role' => [
                'type' => 'enum',
                'options' => $this->roles,
                'validation' => ['required' => true]
            ],

            // max in the db definition is 255, but the hash function changes the length of the input
            'password_hash' => [
                'label' => 'password',
                'type' => 'character varying',
                'fieldType' => 'password',
                'max' => 50,
                'min' => 12,
                'validation' => ['required' => true, 'alphaspace' => true],
                'persist' => false
            ]

        ];
    }

    public function getFormFields(string $databaseAction = 'insert'): array
    {
        if ($databaseAction != 'insert' && $databaseAction != 'update') {
            throw new \Exception("databaseAction must be insert or update: " . $databaseAction);
        }

        $formFields = [];

        foreach ($this->getColumns() as $databaseColumnModel) {
            $name = $databaseColumnModel->getName();
            if (!$databaseColumnModel->isPrimaryKey()) {
                $formFields[$name] = FormHelper::getFieldFromDatabaseColumn($databaseColumnModel);
            }
            if ($name == 'password_hash') {
                $formFields['confirm_password_hash'] = [
                    'label' => 'confirm password hash',
                    'tag' => 'input',
                    'type' => 'password',
                    'maxlength' => 50,
                    'minlength' => 12,
                    'persist' => false
                ];
            }
        }

        if ($databaseAction == 'insert') {
            // note put required first so it's validated first
            $formFields['password_hash']['validation'] = [
                'required' => true,
                'minlength' => 12,
            ];
            $formFields['confirm_password_hash']['validation'] = [
                'required' => true,
                'minlength' => 12,
            ];

        } else { // update

            $formFields['password_hash']['label'] = 'Change Password (leave blank to keep existing password)';
            $formFields['confirm_password_hash']['label'] = 'Confirm New Password';
            // override post method
            $formFields['_METHOD'] = FormHelper::getPutMethodField();
        }

        $formFields['submit'] = FormHelper::getSubmitField();

        return $this->setPersistPasswords($formFields);

    }

    private function setPersistPasswords(array &$fields): array
    {
        if (!isset($_SESSION['validationErrors']['password_hash']) && !isset($_SESSION['validationErrors']['confirm_password_hash'])) {
            $fields['password_hash']['persist'] = true;
            $fields['confirm_password_hash']['persist'] = true;
        }
        return $fields;
    }

    private function validateRole(string $roleCheck): bool
    {
        return in_array($roleCheck, $this->roles);
    }

    /**
     * If a blank password is passed, the password field is not updated
     * @param int $id
     * @param array $columnValues
     * @return resource
     * @throws \Exception
     */
    public function updateByPrimaryKey(array $columnValues, $primaryKeyValue, $primaryKeyName = 'id')
    {
        if (!$this->validateRole($columnValues['role'])) {
            throw new \Exception("Admin being updated with invalid role ".$columnValues['role']);
        }
        $q = new QueryBuilder("UPDATE admins SET name = $1, username = $2, role = $3", $columnValues['name'], $columnValues['username'], $columnValues['role']);
        $argNum = 4;
        if (strlen($columnValues['password_hash']) > 0) {
            $q->add(", password_hash = $$argNum", password_hash($columnValues['password_hash'], PASSWORD_DEFAULT));
            $argNum++;
        }
        $q->add(" WHERE id = $$argNum RETURNING id", $primaryKeyValue);
        return $q->execute();
    }

    public function checkRecordExistsForUsername(string $username): bool
    {
        $q = new QueryBuilder("SELECT id FROM admins WHERE username = $1", $username);
        $res = $q->execute();
        return pg_num_rows($res) > 0;
    }

    public function selectForUsername(string $username)
    {
        $q = new QueryBuilder("SELECT * FROM admins WHERE username = $1", $username);
        return $q->execute();
    }

    // If a null password is passed, the password field is not checked
    public function hasRecordChanged(array $columnValues, $primaryKeyValue, array $skipColumns = null, array $record = null): bool
    {
        if (strlen($columnValues['password_hash']) == 0) {
            $skipColumns[] = 'password_hash';
        } else {
            $columnValues['password_hash'] = password_hash($columnValues['password_hash'], PASSWORD_DEFAULT);
        }

        return parent::hasRecordChanged($columnValues, $primaryKeyValue, $skipColumns);
    }

    public function insert(array $columnValues)
    {
        $columnValues['password_hash'] = password_hash($columnValues['password_hash'], PASSWORD_DEFAULT);
        return parent::insert($columnValues);
    }
}
