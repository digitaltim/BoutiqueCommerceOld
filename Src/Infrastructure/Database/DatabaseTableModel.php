<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure\Database;

use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Postgres;
use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\InsertBuilder;
use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\InsertUpdateBuilder;
use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\QueryBuilder;
use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\UpdateBuilder;
use It_All\BoutiqueCommerce\Src\Infrastructure\UserInterface\FormHelper;
use function It_All\BoutiqueCommerce\Src\Infrastructure\Utilities\printPreArray;
use It_All\BoutiqueCommerce\Src\Infrastructure\Utilities\ValidationService;

class DatabaseTableModel
{
    /** @var  array of column model objects */
    protected $columns;

    /** @var string  */
    protected $tableName;

    /** @var string or false if no primary key column exists */
    protected $primaryKeyColumnName;

    /**
     * @var array of columnNames with UNIQUE constraint
     * NOTE this does not handle multi-column unique constraints
     */
    private $uniqueColumns;

    private $skipPrimaryKeyColumnInFormFields;

    /**
     * @var array. the form fields can vary based on whether the action is insert or update, and every page will not use a form, so they are not constructed upon instantiation
     */
    protected $formFields;

    public function __construct(string $tableName, $skipPrimaryKeyColumnInFormFields = true)
    {
        $this->tableName = $tableName;
        $this->skipPrimaryKeyColumnInFormFields = $skipPrimaryKeyColumnInFormFields;
        $this->primaryKeyColumnName = false; // default
        $this->uniqueColumns = [];
        $this->setConstraints(); // $this->primaryKeyColumnName will be updated if exists\
        $this->setColumns();
    }

    private function setConstraints()
    {
        $q = new QueryBuilder("SELECT ccu.column_name, tc.constraint_type FROM INFORMATION_SCHEMA.constraint_column_usage ccu JOIN information_schema.table_constraints tc ON ccu.constraint_name = tc.constraint_name WHERE tc.table_name = ccu.table_name AND ccu.table_name = $1", $this->tableName);
        $qResult = $q->execute();
        while ($qRow = pg_fetch_assoc($qResult)) {
            switch($qRow['constraint_type']) {
                case 'PRIMARY KEY':
                    $this->primaryKeyColumnName = $qRow['column_name'];
                    break;
                case 'UNIQUE':
                    $this->uniqueColumns[] = $qRow['column_name'];
            }
        }
    }

    protected function setColumns()
    {
        $rs = Postgres::getTableMetaData($this->tableName);
        while ($columnInfo = pg_fetch_assoc($rs)) {
            $columnInfo['is_unique'] = in_array($columnInfo['column_name'], $this->uniqueColumns);
            $c = new DatabaseColumnModel($this, $columnInfo);
            $this->columns[] = $c;
        }
    }

    public function select(string $columns = '*', string $orderByColumn = null, bool $orderByAsc = true)
    {
        $q = new QueryBuilder("SELECT $columns FROM $this->tableName");
        if ($orderByColumn != null) {
            if ($orderByColumn == 'PRIMARYKEY') {
                if ($this->primaryKeyColumnName === false) {
                    throw new \Exception("Cannot order by Primary Key since it does not exist for table ".$this->tableName);
                }
                $orderByColumn = $this->primaryKeyColumnName;
            }
            $orderByString = " ORDER BY $orderByColumn";
            if (!$orderByAsc) {
                $orderByString .= " DESC";
            }
            $q->add($orderByString);
        }
        return $q->execute();
    }

    public function selectForPrimaryKey($primaryKeyValue)
    {
        $primaryKeyName = $this->getPrimaryKeyColumnName();

        $q = new QueryBuilder("SELECT * FROM $this->tableName WHERE $primaryKeyName = $1", $primaryKeyValue);
        if(!$res = $q->execute()) {
            // this is for a query error not a not found condition
            throw new \Exception("Invalid $primaryKeyName for $this->table: $primaryKeyValue");
        }
        return pg_fetch_assoc($res); // returns false if not records are found
    }

    public function updateByPrimaryKey(array $columnValues, $primaryKeyValue)
    {
        $primaryKeyName = $this->getPrimaryKeyColumnName();

        if (!$currentRow = $this->selectForPrimaryKey($primaryKeyValue)) {
            throw new \Exception("Invalid $primaryKeyName $primaryKeyValue for $this->tableName");
        }
        $ub = new UpdateBuilder($this->tableName, $primaryKeyName, $primaryKeyValue);
        $this->addColumnsToBuilder($ub, $columnValues, $currentRow);
        return $ub->execute();
    }

    public function insert(array $columnValues)
    {
        $ib = new InsertBuilder($this->tableName);
        $ib->setPrimaryKeyName($this->getPrimaryKeyColumnName());
        $this->addColumnsToBuilder($ib, $columnValues);
        return $ib->execute();
    }

    public function deleteByPrimaryKey($primaryKeyValue, string $returning = null)
    {
        $query = "DELETE FROM $this->tableName WHERE ".$this->getPrimaryKeyColumnName()." = $1";
        if ($returning !== null) {
            $query .= "RETURNING $returning";
        }
        $q = new QueryBuilder($query, $primaryKeyValue);

        return $q->execute();
    }

    protected function validateDatabaseActionString(string $databaseAction = 'insert')
    {
        if ($databaseAction != 'insert' && $databaseAction != 'update') {
            throw new \Exception("databaseAction must be insert or update ".$databaseAction);
        }
    }

    public function getValidationRules(string $databaseAction = 'insert'): array
    {
        $this->validateDatabaseActionString($databaseAction);
        return ValidationService::getRules($this->getFormFields($databaseAction));
    }

    public function setFormFields(string $databaseAction = 'insert')
    {
        $this->validateDatabaseActionString($databaseAction);

        $this->formFields = [];

        foreach ($this->getColumns() as $column) {
            $name = $column->getName();
            if (!$this->skipPrimaryKeyColumnInFormFields || ($this->skipPrimaryKeyColumnInFormFields && !$column->isPrimaryKey()) ) {
                $this->formFields[$name] = FormHelper::getFieldFromDatabaseColumn($column);
            }
        }

        if ($databaseAction == 'update') {
            // override post method
            $this->formFields['_METHOD'] = FormHelper::getPutMethodField();
        }

        $this->formFields['submit'] = FormHelper::getSubmitField();
    }

    public function hasRecordChanged(array $fieldValues, $primaryKeyValue, array $skipColumns = null, array $record = null): bool
    {
        if ($skipColumns == null) {
            $skipColumns = [];
        }
        if ($this->primaryKeyColumnName && $this->skipPrimaryKeyColumnInFormFields) {
            $skipColumns[] = $this->primaryKeyColumnName;
        }
        if (!is_array($record)) {
            $record = $this->selectForPrimaryKey($primaryKeyValue);
        }

        foreach ($this->columns as $column) {
            $columnName = $column->getName();
            if (!in_array($columnName, $skipColumns)) {
                if ($record[$columnName] != $fieldValues[$columnName]) {
                    return true;
                }
            }
        }
        return false;
    }

    protected function addColumnsToBuilder(InsertUpdateBuilder $builder, array $columnValues)
    {
        foreach ($this->columns as $column) {
            $columnName = $column->getName();

            if (!$this->skipPrimaryKeyColumnInFormFields || ($this->skipPrimaryKeyColumnInFormFields && !$column->isPrimaryKey()) ) {
                if (isset($columnValues[$columnName])) {
                    $columnValue = $columnValues[$columnName];
                    if ($column->isBoolean() && $columnValue == 'on') {
                        $columnValue = 't';
                    }
                    if (strlen($columnValue) == 0) {
                        $columnValue = $this->handleBlankValue($column);
                    }
                } else {
                    $columnValue = $this->handleBlankValue($column);
                }
                $builder->addColumn($columnName, $columnValue);
            }
        }
    }

    private function handleBlankValue(DatabaseColumnModel $column)
    {
        // set to null if field is nullable
        if ($column->getIsNullable()) {
            return null;
        }

        // set to 0 if field is numeric
        if ($column->isNumericType()) {
            return 0;
        }

        // set to f if field is boolean
        if ($column->isBoolean()) {
            return 'f';
        }

        return '';
    }

    /**
     * @param bool $plural if false last character is removed
     * @return string
     */
    public function getFormalTableName(bool $plural = true): string
    {
        $name = ucwords(str_replace('_', ' ', $this->tableName));
        if (!$plural) {
            $name = substr($name, 0, strlen($this->tableName) - 1);
        }
        return $name;
    }

    // getters

    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @return string defaults to 'id', can be overridden by children
     */
    public function getPrimaryKeyColumnName(): string
    {
        return $this->primaryKeyColumnName;
    }

    public function getColumns(): array
    {
        if (count($this->columns) == 0) {
            throw new \Exception('No columns in model '.$this->tableName);
        }
        return $this->columns;
    }

    public function getColumnByName(string $columnName): DatabaseColumnModel
    {
        foreach ($this->columns as $column) {
            if ($column->getName() == $columnName) {
                return $column;
            }
        }
        throw new \Exception('Search for column '.$columnName.' in table '.$this->tableName.' failed.');
    }

    public function getFormFields(string $databaseAction = 'insert'): array
    {
        if (!isset($this->formFields)) {
            $this->setFormFields($databaseAction);
        }

        return $this->formFields;
    }
}
