<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure;

use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\InsertBuilder;
use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\InsertUpdateBuilder;
use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\QueryBuilder;
use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\UpdateBuilder;

abstract class Model
{
    protected $columns;
    private $tableName;

    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;
        $this->setColumns();
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    abstract protected function setColumns();

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function select(string $columns = '*')
    {
        $q = new QueryBuilder("SELECT $columns FROM $this->tableName");
        return $q->execute();
    }

    public function selectForPrimaryKey($primaryKeyValue, string $primaryKeyName = 'id')
    {
        $q = new QueryBuilder("SELECT * FROM $this->tableName WHERE $primaryKeyName = $1", $primaryKeyValue);
        if(!$res = $q->execute()) {
            throw new \Exception("Invalid $primaryKeyName for $this->table: $primaryKeyValue");
        }

        return pg_fetch_assoc($res);
    }

    public function updateByPrimaryKey(array $columnValues, $primaryKeyValue, $primaryKeyName = 'id')
    {
        if (!$currentRow = $this->selectForPrimaryKey($primaryKeyValue)) {
            throw new \Exception("Invalid $primaryKeyName $primaryKeyValue for $this->tableName");
        }
        $ub = new UpdateBuilder($this->tableName, $primaryKeyName, $primaryKeyValue);
        $this->addColumnsToBuilder($ub, $columnValues, $currentRow);
        return $ub->execute();
    }

    public function insert($columnValues)
    {
        $ib = new InsertBuilder($this->tableName);
        $this->addColumnsToBuilder($ib, $columnValues);
        return ($ib->execute()) ? true : false;
    }

    public function deleteByPrimaryKey(
        $primaryKeyValue,
        string $primaryKeyName = 'id',
        string $returning = null
    )
    {
        $query = "DELETE FROM $this->tableName WHERE $primaryKeyName = $1";
        if ($returning !== null) {
            $query .= "RETURNING $returning";
        }
        $q = new QueryBuilder($query, $primaryKeyValue);

        return $q->execute();
    }

    public function hasRecordChanged(array $columnValues, $primaryKeyValue, string $primaryKeyName = 'id'): bool
    {
        $record = $this->selectForPrimaryKey($primaryKeyValue, $primaryKeyName);

        foreach ($this->columns as $columnName => $columnInfo) {
            if ($record[$columnName] != $columnValues[$columnName]) {
                return true;
            }
        }
        return false;
    }

    private function addColumnsToBuilder(
        InsertUpdateBuilder $builder,
        array $columnValues,
        array $updateRow=null
    )
    {
        foreach ($this->columns as $columnName => $columnInfo) {

            if (isset($columnValues[$columnName])) {
                $columnValue = $columnValues[$columnName];
                if ($this->isBooleanColumn($columnName) && $columnValue == 'on') {
                    $columnValue = 't';
                }
                if (strlen($columnValue) == 0) {
                    $columnValue = $this->handleBlankValue($columnName);
                }
            } else {
                $columnValue = $this->handleBlankValue($columnName);
            }
            $builder->addColumn($columnName, $columnValue);
        }
    }

    private function isBooleanColumn(string $columnName): bool
    {
        return isset($this->columns[$columnName]['isBoolean']) && $this->columns[$columnName]['isBoolean'];
    }

    private function isNullableColumn(string $columnName): bool
    {
        return isset($this->columns[$columnName]['isNullable']) && $this->columns[$columnName]['isNullable'];
    }

    private function isNumericTypeColumn(string $columnName): bool
    {
        return isset($this->columns[$columnName]['isNumericType']) && $this->columns[$columnName]['isNumericType'];
    }

    private function handleBlankValue(string $columnName)
    {
        // set to null if field is nullable
        if ($this->isNullableColumn($columnName)) {
            return null;
        }
        // set to 0 if field is numeric
        if ($this->isNumericTypeColumn($columnName)) {
            return 0;
        }
        // set to f if field is boolean
        if ($this->isBooleanColumn($columnName)) {
            return 'f';
        }
        return '';
    }
}
