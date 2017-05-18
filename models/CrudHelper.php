<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\UI\Views\Admin\CRUD;

use It_All\BoutiqueCommerce\Models\DbTable;
use It_All\BoutiqueCommerce\Postgres;

class CrudHelper
{
    static public function getModel(string $tableName, Postgres $db)
    {
        $class = 'It_All\BoutiqueCommerce\Models\\'.ucfirst($tableName);
        try {
            $model = (class_exists($class)) ? new $class($db) : new DbTable($tableName, $db);
            return $model;
        } catch (\Exception $e) {
            throw new \Exception('Invalid Table Name: ' . $tableName);
        }
    }
}
