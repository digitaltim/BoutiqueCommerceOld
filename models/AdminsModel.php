<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Models;

use It_All\BoutiqueCommerce\Utilities\Database\QueryBuilder;

class AdminsModel extends DbTable
{
    function __construct()
    {
        parent::__construct('admins');
        $this->allowInsert = true;
        $this->allowUpdate = false;
        $this->allowDelete = false;
    }

    public function getFields()
    {
        return [

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

    public function checkRecordExistsForUsername(string $username)
    {
        $q = new QueryBuilder("SELECT id FROM admins WHERE username = $1", $username);
        $q->execute();
        return $q->checkRecordsExist();
    }

    public function getAdminDataForUsername(string $username)
    {
        $q = new QueryBuilder("SELECT a.id as admin_id, a.role, a.password_hash, e.id as employee_id, e.fname, e.lname FROM admins a LEFT OUTER JOIN employees e ON a.employee_id = e.id  WHERE a.username = $1", $username);
        return $q->execute();
    }

    public function getAdminDataForId(int $id)
    {
        $q = new QueryBuilder("SELECT a.username, a.role, e.id as employee_id, e.fname, e.lname FROM admins a LEFT OUTER JOIN employees e ON a.employee_id = e.id  WHERE a.id = $1", $id);
        $Res = $q->execute();
        return pg_fetch_assoc($Res);
    }

    public function getAdminsData()
    {
        $q = new QueryBuilder("SELECT a.id as admin_id, a.username, a.role, e.fname, e.lname FROM admins a LEFT OUTER JOIN employees e ON a.employee_id = e.id  ORDER BY username");
        return $q->execute();
    }

}