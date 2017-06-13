<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Admins;

use It_All\BoutiqueCommerce\Src\Infrastructure\Model;
use It_All\BoutiqueCommerce\Src\Infrastructure\UserInterface\FormHelper;
use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\QueryBuilder;
use Psr\Log\InvalidArgumentException;

class AdminsModel extends Model
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
        parent::__construct('admins');
    }

    protected function setColumns()
    {
        $this->columns = [

            'username' => [
                'tag' => 'input',
                'label' => 'Username',
                'validation' => [
                    'required' => true,
                    '%^[a-zA-Z]+$%' => 'only letters',
                    'minlength' => 5,
                    'maxlength' => 20
                ],
                'attributes' => [
                    'id' => 'username',
                    'name' => 'username',
                    'type' => 'text',
                    'size' => '15',
                    'maxlength' => '20',
                    'value' => ''
                ]
            ],

            'name' => [
                'tag' => 'input',
                'label' => 'Name',
                'validation' => [
                    'required' => true,
                    'alphaspace' => true,
                    'maxlength' => 50
                ],
                'attributes' => [
                    'id' => 'name',
                    'name' => 'name',
                    'type' => 'text',
                    'size' => '15',
                    'maxlength' => '50',
                    'value' => ''
                ]
            ],

            'role' => [
                'tag' => 'select',
                'label' => 'Role',
                'validation' => ['required' => true],
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

    public function getFormFields(string $dbAction = 'insert'): array
    {
        if ($dbAction != 'insert' && $dbAction != 'update') {
            throw new InvalidArgumentException("dbAction must be insert or update: ".$dbAction);
        }

        $fields = array_merge($this->columns, [

            'confirm_password_hash' => [
                'tag' => 'input',
                'label' => 'Confirm Password',
                'validation' => ['minlength' => 12, 'confirm' => true],
                'attributes' => [
                    'type' => 'password',
                    'name' => 'confirm_password_hash',
                    'size' => '20',
                    'maxlength' => '30',
                ],
                'persist' => false,
            ],

            'submit' => FormHelper::getSubmitField()
        ]);

        $fields['password_hash']['persist'] = false;

        if ($dbAction == 'insert') {
            // note put required first so it's validated first
            $fields['password_hash']['validation'] = [
                'required' => true,
                'minlength' => 12,
            ];
            $fields['confirm_password_hash']['validation'] = [
                'required' => true,
                'minlength' => 12,
            ];
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
            $q->add(", password_hash = $$argNum", password_hash($columnValues['password'], PASSWORD_DEFAULT));
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
    public function hasRecordChanged(array $columValues, $primaryKeyValue, string $primaryKeyName = 'id', array $skipColumns = null, array $record = null): bool
    {
        if (strlen($columValues['password_hash']) == 0) {
            $skipColumns[] = 'password_hash';
        } else {
            $columValues['password_hash'] = password_hash($columValues['password_hash'], PASSWORD_DEFAULT);
        }

        return parent::hasRecordChanged($columValues, $primaryKeyValue, $primaryKeyName, $skipColumns);
    }
}