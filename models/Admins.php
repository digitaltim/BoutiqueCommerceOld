<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Models;

use It_All\BoutiqueCommerce\Postgres;
use It_All\BoutiqueCommerce\Utilities\Database\QueryBuilder;

class Admins extends DbTable
{
    function __construct(Postgres $db)
    {
        parent::__construct('admins', $db);
        $this->allowInsert = false;
        $this->allowUpdate = false;
        $this->allowDelete = false;
    }

    static public function getAdminDataForUsername(string $username)
    {
        $q = new QueryBuilder("SELECT a.id as admin_id, a.permissions, a.password_hash, e.id as employee_id, e.fname, e.lname FROM admins a LEFT OUTER JOIN employees e ON a.employee_id = e.id  WHERE a.username = $1", $username);
        return $q->execute();
    }

    static public function getAdminDataForId(int $id)
    {
        $q = new QueryBuilder("SELECT a.username, a.permissions, e.id as employee_id, e.fname, e.lname FROM admins a LEFT OUTER JOIN employees e ON a.employee_id = e.id  WHERE a.id = $1", $id);
        $Res = $q->execute();
        return pg_fetch_assoc($Res);
    }

    static public function getAdminsData()
    {
        $q = new QueryBuilder("SELECT a.id as admin_id, a.username, a.permissions, e.fname, e.lname FROM admins a LEFT OUTER JOIN employees e ON a.employee_id = e.id  ORDER BY username");
        return $q->execute();
    }

}