<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Models;

use It_All\BoutiqueCommerce\Utilities\Database;

/** note on null and '':
 * if the column is nullable and a blank value is being inserted (updated to), change it to null
 * of the column is not nullable, generally ok to insert '', otherwise set the column to required
 * in the table child model
*/

class DbTable {
    private $tableName;
    private $dbTableMetaRes;

    /** @var  array of column objects belonging to table */
    protected $columns;

    /** @var array $columnName => $defaultValue;
     * set initial field values for insert form
     * this will override the default value set in pg
     * and be overridden by posted insert values
     */
    protected $defaultColumnValues = [];

    /** @var array $columnName => $value; hidden field added in insert form. things like enter_date that gets set to now */
    protected $hiddenInsertColumnValues = [];

    /** @var array $columnName => $value; hidden field added in insert form */
    protected $hiddenUpdateColumnValues = [];

    /** @var array exclude columns in insert form and therefore insert query
     * note primary key gets added here by default
     */
    protected $excludedInsertColumns = [];

    /** @var array exclude columns in update form and therefore update query
     * note primary key gets added here by default
     */
    protected $excludedUpdateColumns = [];

    /** @var array set fields disabled in insert form */
    protected $disabledInsertColumns = [];

    /** @var array set fields disabled in update form */
    protected $disabledUpdateColumns = [];

    /** @var  column name or false */
    private $primaryKeyColumn;

    private $orderBy = '';

    /**
     * @var int default 1000
     */
    public $selectLimit = 1000;

    protected $allowInsert = true;

    /** @var bool only works if there is a primary key column */
    protected $allowUpdate = true;

    /** @var bool only works if there is a primary key column */
    protected $allowDelete = true;

    /** @var array of general (table-level) validation error messages */

    function __construct(string $tableName)
    {
        $this->tableName = $tableName;
        if(!$this->dbTableMetaRes = \It_All\BoutiqueCommerce\Postgres::getTableMetaData($this->tableName)) {
            throw new \Exception("getTableMetaData failed for $tableName");
        }
        $this->setColumns();
        $this->setPrimaryKeyColumn();
        if ($this->primaryKeyColumn !== false) {
            $this->excludedInsertColumns[] = $this->primaryKeyColumn;
            $this->excludedUpdateColumns[] = $this->primaryKeyColumn;
            $this->orderBy = "$this->primaryKeyColumn DESC"; // default
        }
    }

    public function getOrderBy(): string
    {
        return $this->orderBy;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getDefaultColumnValues(): array
    {
        return $this->defaultColumnValues;
    }

    private function verifyInsertOrUpdate(string $insertOrUpdate)
    {
        if ($insertOrUpdate != 'insert' && $insertOrUpdate != 'update') {
            throw new \Exception("Invalid argument passed, must be insert or update");
        }
    }

    /**
     * @param string $dbAction 'insert' or 'update'
     * @return array
     */
    public function getHiddenColumnValues(string $insertOrUpdate): array
    {
        $this->verifyInsertOrUpdate($insertOrUpdate);
        return $this->{"hidden".ucwords($insertOrUpdate)."ColumnValues"};
    }

    /**
     * @param string $queryAction 'insert' or 'update'
     * @return array
     */
    public function getExcludedColumns(string $insertOrUpdate): array
    {
        $this->verifyInsertOrUpdate($insertOrUpdate);
        return $this->{"excluded".ucwords($insertOrUpdate)."Columns"};
    }

    /**
     * @param string $queryAction 'insert' or 'update'
     * @return array
     */
    public function getDisabledColumns(string $insertOrUpdate): array
    {
        $this->verifyInsertOrUpdate($insertOrUpdate);
        return $this->{"disabled".ucwords($insertOrUpdate)."Columns"};
    }

    private function setPrimaryKeyColumn()
    {
        $q = new Database\QueryBuilder("SELECT column_name FROM INFORMATION_SCHEMA.constraint_column_usage WHERE table_name = $1 AND constraint_name = $2", $this->tableName, $this->tableName.'_pkey');
        $this->primaryKeyColumn = $q->getOne(); // false if doesn't exist
    }

    public function getPrimaryKeyColumn()
    {
        return $this->primaryKeyColumn;
    }

    public function isPrimaryKeyColumn(string $columnName): bool
    {
        return $this->primaryKeyColumn === $columnName;
    }

    /**
     * @return bool
     * can be overridden by child table classes to grant permission to other users
     */
    protected function hasInsertPermission(): bool
    {
        // return isset($_SESSION['permissions']) && $_SESSION['permissions'] == 'owner';
        return true;
    }

    /**
     * @return bool
     * can be overridden by child table classes to grant permission to other users
     */
    private function hasUpdatePermission(): bool
    {
        return $this->hasInsertPermission();
    }

    /**
     * @return bool
     * can be overridden by child table classes to grant permission to other users
     */
    private function hasDeletePermission(): bool
    {
        return $this->hasInsertPermission();
    }

    public function isInsertAllowed(): bool
    {
        return $this->hasInsertPermission() && $this->allowInsert;
    }

    public function isUpdateAllowed(): bool
    {
        return $this->hasUpdatePermission() && $this->getPrimaryKeyColumn() !== false && $this->allowUpdate;
    }

    public function isDeleteAllowed(): bool
    {
        return $this->hasDeletePermission() && $this->getPrimaryKeyColumn() !== false && $this->allowUpdate;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    private function setColumns()
    {
        while ($columnInfo = pg_fetch_assoc($this->dbTableMetaRes)) {
            $c = new DbColumn($this, $columnInfo);
            $this->columns[] = $c;
        }
    }

    public function getColumnByName(string $columnName)
    {
        foreach ($this->columns as $c) {
            if ($c->getName() == $columnName) {
                return $c;
            }
        }
        return false;
    }

    public function getRowCountForColumnValue(string $columnName, string $value): int
    {
        if (!$this->getColumnByName($columnName)) {
            throw new \Exception("Invalid column name: $columnName for table: $this->tableName");
        }
        $q = new Database\QueryBuilder("SELECT * FROM $this->tableName WHERE $columnName = $1", $value);

        $res = $q->execute();
        return pg_num_rows($res);
    }

    public function doesColumnValueExist(string $columnName, string $value): bool
    {
        return $this->getRowCountForColumnValue($columnName, $value) > 0;
    }

    private function handleBlankValue(DbColumn $column)
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

    private function addColumnsToBuilder(Database\QueryBuilder $qb, array $columnValues, array $updateRow = null)
    {
        if (get_class($qb) == 'InsertBuilder') {
            $isInsert = true;
            $excludeColumns = $this->getExcludedColumns('insert');
        } else {
            $isInsert = false;
            $excludeColumns = $this->getExcludedColumns('update');
        }
        foreach ($this->columns as $c) {
            $name = $c->getName();
            if (!in_array($name, $excludeColumns)) {
                if (isset($columnValues[$name])) {
                    $val = $columnValues[$name];
                    if ($c->isBoolean() && $val == 'on') {
                        $val = 't';
                    }
                    if (strlen($val) == 0) {
                        $val = $this->handleBlankValue($c);
                    }
                } else {
                    // if column does not exist in argument force to proper blank value
                    // if blank not allowed the column should be set required in child table model
                    $val = $this->handleBlankValue($c);
                }
                // add all columns for insert and only changed columns for update
                if ($isInsert || ($val != $updateRow[$name])) {
                    $qb->addColumn($name, $val);
                }
            }
        }
    }

    public function selectRowByPrimaryKey(string $pkValue)
    {
        if (!$pkColumn = $this->getPrimaryKeyColumn()) {
            throw new \Exception("Primary Key Column does not exist for table ".$this->tableName);
        }
        $q = new Database\QueryBuilder("SELECT * FROM ".$this->tableName." WHERE $pkColumn = $1", $pkValue);

        return $q->execute();
    }

    public function insert(array $columnValues)
    {
        if (!$this->allowInsert) {
            throw new \Exception("Insert not allowed on table $this->tableName");
        }
        $ib = new Database\InsertBuilder($this->tableName);
        $this->addColumnsToBuilder($ib, $columnValues);
        return $ib->execute();
    }

    public function update(array $columnValues, string $pkValue)
    {
        if (!$currentRes = $this->selectRowByPrimaryKey($pkValue)) {
            throw new \Exception("INVALID primary key $pkValue passed");
        }
        $currentRow = pg_fetch_assoc($currentRes);
        if (!$this->isUpdateAllowed()) {
            throw new \Exception("Update not allowed on table $this->tableName");
        }
        $ub = new Database\UpdateBuilder($this->tableName, $this->getPrimaryKeyColumn(), $pkValue);
        $this->addColumnsToBuilder($ub, $columnValues, $currentRow);
        if (count($ub->args) == 0) {
            throw new \Exception("No changes made.");
        }
        return $ub->execute();
    }

    public function delete(string $pkValue): bool
    {
        if(!$this->allowDelete) {
            throw new \Exception("Deletions not allowed for table $this->tableName");
        }
        if (!$pkField = $this->getPrimaryKeyColumn()) {
            throw new \Exception("No primary key for table $this->tableName");
        }
        $q = new Database\QueryBuilder("DELETE FROM $this->tableName WHERE $pkField = \$1", $pkValue);

        if ($res = $q->execute()) {
            if (pg_affected_rows($res) == 1) {
                return true;
            } else {
                throw new \Exception("$pkField $pkValue not found for table $this->tableName");
            }
        } else {
            throw new \Exception("Delete query failed. See error log.");
        }
    }

    /**
     * @param string $where
     * @param string $columns
     * @param null $limitOverride 0 for no limit, integer for specific limit, otherwise uses default table limit
     * @return
     * NOTE: there may be some weaknesses/incompatibilites in the WHERE clause, especially with null.
     */
    public function select(string $columns = "*", array $whereArr = [], string $orderByOverride = null, string $limitOverride = null)
    {
        $q = new Database\QueryBuilder("SELECT * FROM $this->tableName");
        if (count($whereArr) > 0) {
            $q->add(" WHERE ");
            $i = 0;
            foreach ($whereArr as $colName => $colValue) {
                if ($this->getColumnByName($colName)) {
                    $i++;
                    if ($i > 1) {
                        $q->add(" AND ");
                    }
                    if ($colValue == 'null') {
                        $colValue = null;
                    }
                    $q->null_eq($colName, $colValue);
                } else {
                    throw new \Exception("INVALID column in WHERE clause");
                }
            }
        }
        $orderBy = ($orderByOverride != null) ? $orderByOverride : $this->orderBy;
        if ($limitOverride === 0) {
            $limit = "";
        } elseif (\It_All\BoutiqueCommerce\Services\Validator::isInteger($limitOverride)) {
            $limit = "LIMIT $limitOverride";
        } else {
            $limit = "LIMIT $this->selectLimit";
        }
        $q->add(" ORDER BY $orderBy $limit");
        return $q->execute();
    }
}
