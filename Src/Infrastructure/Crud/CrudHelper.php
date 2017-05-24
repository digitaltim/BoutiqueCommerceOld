<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure\Crud;

use It_All\BoutiqueCommerce\Src\Infrastructure\Database\DbTable;
use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Postgres;

class CrudHelper
{
    public static function getModel(string $tableName, Postgres $db)
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
