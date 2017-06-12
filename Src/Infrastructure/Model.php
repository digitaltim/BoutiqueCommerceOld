<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure;

use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\InsertBuilder;
use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\InsertUpdateBuilder;
use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\QueryBuilder;
use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\UpdateBuilder;
use function It_All\BoutiqueCommerce\Src\Infrastructure\Utilities\printPreArray;
use It_All\BoutiqueCommerce\Src\Infrastructure\Utilities\ValidationService;

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

    abstract public function getFormFields(string $dbAction = 'insert'): array;

    public function select(string $columns = '*')
    {
        $q = new QueryBuilder("SELECT $columns FROM $this->tableName");
        return $q->execute();
    }

    public function selectForPrimaryKey($primaryKeyValue, string $primaryKeyName = 'id')
    {
        $q = new QueryBuilder("SELECT * FROM $this->tableName WHERE $primaryKeyName = $1", $primaryKeyValue);
        if(!$res = $q->execute()) {
            // this is for a query error not a not found condition
            throw new \Exception("Invalid $primaryKeyName for $this->table: $primaryKeyValue");
        }
        return pg_fetch_assoc($res); // returns false if not records are found
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

    public function insert(array $columnValues, string $primaryKeyName = null)
    {
        $ib = new InsertBuilder($this->tableName);
        if ($primaryKeyName != null) {
            $ib->setPrimaryKeyName($primaryKeyName);
        }
        $this->addColumnsToBuilder($ib, $columnValues);
        return $ib->execute();
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

    public function getValidationRules($formType = 'insert'): array
    {
        if ($formType != 'insert' && $formType != 'update') {
            throw new InvalidArgumentException("formType must be insert or update ".$formType);
        }

        return ValidationService::getRules($this->getFormFields($formType));
    }

    public function hasRecordChanged(array $columnValues, $primaryKeyValue, string $primaryKeyName = 'id', array $skipColumns = null, array $record = null): bool
    {
        if (!is_array($record)) {
            $record = $this->selectForPrimaryKey($primaryKeyValue, $primaryKeyName);
        }

        foreach ($this->columns as $columnName => $columnInfo) {
            if (is_null($skipColumns) || !in_array($columnName, $skipColumns)) {
                if ($record[$columnName] != $columnValues[$columnName]) {
                    return true;
                }
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
