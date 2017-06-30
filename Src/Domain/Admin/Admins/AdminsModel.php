<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Admins;

use It_All\BoutiqueCommerce\Src\Infrastructure\Database\DatabaseTableModel;
use It_All\BoutiqueCommerce\Src\Infrastructure\UserInterface\FormHelper;
use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\QueryBuilder;

class AdminsModel extends DatabaseTableModel
{
    private $roles; // array of existing roles

    public function __construct()
    {
        parent::__construct('admins');
        $this->roles = $this->getColumnByName('role')->getEnumOptions();
    }

    private function getConfirmPasswordHashField(string $label, array $validation): array
    {
        $field = [
            'label' => $label,
            'tag' => 'input',
            'attributes' => [
                'name' => 'confirm_password_hash',
                'id' => 'confirm_password_hash',
                'type' => 'password',
                'maxlength' => 50
            ],
            'validation' => $validation
        ];

        return $field;
    }

    /**
     * override for customization
     * @param string $databaseAction
     * @return array
     */
    public function setFormFields(string $databaseAction = 'insert')
    {
        $this->validateDatabaseActionString($databaseAction);

        $this->formFields = [];

        if ($databaseAction == 'insert') {

            $passwordHashLabel = 'password';
            $confirmPasswordHashLabel = 'confirm password';

            // same validation for pw and conf pw
            // note put required first so it's validated first
            $passwordHashFieldValidation = [
                'required' => true,
                'minlength' => 12,
                'maxlength' => 50
            ];
            $confirmPasswordHashFieldValidation = array_merge($passwordHashFieldValidation, ['confirm' => true]);


        } else { //update

            $passwordHashLabel = 'change password (leave blank to keep existing password)';
            $confirmPasswordHashLabel = 'confirm new password';

            // same validation for pw and conf pw
            $passwordHashFieldValidation = [
                'minlength' => 12,
                'maxlength' => 50
            ];
            $confirmPasswordHashFieldValidation = array_merge($passwordHashFieldValidation, ['confirm' => true]);

            // override post method
            $this->formFields['_METHOD'] = FormHelper::getPutMethodField();
        }

        foreach ($this->getColumns() as $databaseColumnModel) {
            $name = $databaseColumnModel->getName();

            // no need to have form field for primary key column
            if (!$databaseColumnModel->isPrimaryKey() && $databaseColumnModel->getName() != 'employee_id') {

                $labelOverride = null;
                $inputTypeOverride = null;
                $validationOverride = null;

                if ($name == 'password_hash') {
                    $labelOverride = $passwordHashLabel;
                    $inputTypeOverride = 'password';
                    $validationOverride = $passwordHashFieldValidation;
                }

                $this->formFields[$name] = FormHelper::getFieldFromDatabaseColumn(
                    $databaseColumnModel,
                    $labelOverride,
                    $inputTypeOverride,
                    $validationOverride
                );

                if ($name == 'password_hash') {
                    $this->formFields['confirm_password_hash'] = $this->getConfirmPasswordHashField($confirmPasswordHashLabel, $confirmPasswordHashFieldValidation);
                }
            }
        }

        $this->formFields['submit'] = FormHelper::getSubmitField();

        $this->setPersistPasswords();
    }

    private function setPersistPasswords()
    {
        if (!isset($_SESSION['validationErrors']['password_hash']) && !isset($_SESSION['validationErrors']['confirm_password_hash'])) {
            $this->formFields['password_hash']['persist'] = true;
            $this->formFields['confirm_password_hash']['persist'] = true;
        } else {
            $this->formFields['password_hash']['persist'] = false;
            $this->formFields['confirm_password_hash']['persist'] = false;
        }
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
    public function hasRecordChanged(array $fieldValues, $primaryKeyValue, array $skipColumns = null, array $record = null): bool
    {
        if (strlen($fieldValues['password_hash']) == 0) {
            $skipColumns[] = 'password_hash';
            $skipColumns[] = 'password_hash';
        } else {
            $columnValues['password_hash'] = password_hash($fieldValues['password_hash'], PASSWORD_DEFAULT);
        }

        return parent::hasRecordChanged($fieldValues, $primaryKeyValue, $skipColumns);
    }

    public function insert(array $columnValues)
    {
        $columnValues['password_hash'] = password_hash($columnValues['password_hash'], PASSWORD_DEFAULT);
        return parent::insert($columnValues);
    }
}
