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
    private $db;
    private $dbTableMetaRes;

    /** @var  array of column objects belonging to table */
    protected $columns;

    /** @var array $columnName => $defaultValue;
     * set initial field values for insert form
     * this will override the default value set in pg
     * and be overridden by posted insert values
     */
    protected $defaultColumnValues = array();

    /** @var array $columnName => $value; hidden field added in insert form. things like enter_date that gets set to now */
    protected $hiddenInsertColumnValues = array();

    /** @var array $columnName => $value; hidden field added in insert form */
    protected $hiddenUpdateColumnValues = array();

    /** @var array exclude columns in insert form and therefore insert query
     * note primary key gets added here by default
     */
    protected $excludedInsertColumns = array();

    /** @var array exclude columns in update form and therefore update query
     * note primary key gets added here by default
     */
    protected $excludedUpdateColumns = array();

    /** @var array set fields disabled in insert form */
    protected $disabledInsertColumns = array();

    /** @var array set fields disabled in update form */
    protected $disabledUpdateColumns = array();

    /** @var  column name or false */
    private $primaryKeyColumn;

    private $orderBy = "id DESC";

    public $selectLimit = 300;
    protected $allowInsert = true;

    /** @var bool only works if there is a primary key column */
    protected $allowUpdate = true;

    /** @var bool only works if there is a primary key column */
    protected $allowDelete = true;

    /** @var array of general (table-level) validation error messages */
    private $validationErrors;

    function __construct(string $tableName, \It_All\BoutiqueCommerce\Postgres $db)
    {
        $this->tableName = $tableName;
        $this->db = $db;
        if(!$this->dbTableMetaRes = $this->db->getTableMetaData($this->tableName)) {
            throw new \Exception("getTableMetaData failed for $tableName");
        }
        $this->setColumns();
        $this->setPrimaryKeyColumn();
        if ($this->primaryKeyColumn !== false) {
            $this->excludedInsertColumns[] = $this->primaryKeyColumn;
            $this->excludedUpdateColumns[] = $this->primaryKeyColumn;
        }
        $this->validationErrors = array();
    }

    public function getOrderBy()
    {
        return $this->orderBy;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getDefaultColumnValues()
    {
        return $this->defaultColumnValues;
    }

    /**
     * @param string $dbAction 'insert' or 'update'
     * @return array
     */
    public function getHiddenColumnValues($dbAction)
    {
        if ($dbAction == 'insert') {
            return $this->hiddenInsertColumnValues;
        }
        elseif ($dbAction == 'update') {
            return $this->hiddenUpdateColumnValues;
        }
        else {
            throw new \Exception("Invalid argument passed");
        }
    }

    /**
     * @param string $queryAction 'insert' or 'update'
     * @return array
     */
    public function getExcludedColumns($dbAction)
    {
        if ($dbAction == 'insert') {
            return $this->excludedInsertColumns;
        }
        elseif ($dbAction == 'update') {
            return $this->excludedUpdateColumns;
        }
        else {
            throw new \Exception("Invalid argument passed");
        }
    }

    /**
     * @param string $queryAction 'insert' or 'update'
     * @return array
     */
    public function getDisabledColumns($dbAction)
    {
        if ($dbAction == 'insert') {
            return $this->disabledInsertColumns;
        }
        elseif ($dbAction == 'update') {
            return $this->disabledUpdateColumns;
        }
        else {
            throw new \Exception("Invalid argument passed");
        }
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

    protected function isPrimaryKeyColumn($columnName)
    {
        return $this->primaryKeyColumn === $columnName;
    }

    /**
     * @return bool
     * can be overridden by child table classes to grant permission to other users
     */
    protected function hasInsertPermission()
    {
        // todo fix
        // return isset($_SESSION['permissions']) && $_SESSION['permissions'] == 'owner';
        return true;
    }

    /**
     * @return bool
     * can be overridden by child table classes to grant permission to other users
     */
    private function hasUpdatePermission()
    {
        return $this->hasInsertPermission();
    }

    /**
     * @return bool
     * can be overridden by child table classes to grant permission to other users
     */
    private function hasDeletePermission()
    {
        return $this->hasInsertPermission();
    }

    public function isInsertAllowed()
    {
        return $this->hasInsertPermission() && $this->allowInsert;
    }

    public function isUpdateAllowed()
    {
        return $this->hasUpdatePermission() && $this->getPrimaryKeyColumn() !== false && $this->allowUpdate;
    }

    public function isDeleteAllowed()
    {
        return $this->hasDeletePermission() && $this->getPrimaryKeyColumn() !== false && $this->allowUpdate;
    }

    public function getTableName()
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

    /** adds general (table-level) validation error to array */
    public function addValidationError($errorMsg)
    {
        $this->validationErrors[] = $errorMsg;
    }

    /**
     * @return array
     * both table level and column level errors
     */
    public function getValidationErrors()
    {
        $vErrors = array();
        // table level
        foreach ($this->validationErrors as $errorMsg) {
            $vErrors[] = array('msg' => $errorMsg);
        }
        // column level
        foreach ($this->columns as $c) {
            $vE = $c->getValidationError();
            if (!is_null($vE)) {
                $vErrors[] = array('column' => $c->getName(), 'msg' => $vE);
            }
        }
        return $vErrors;
    }

    public function getColumn($columnName)
    {
        foreach ($this->columns as $c) {
            if ($c->getName() == $columnName) {
                return $c;
            }
        }
        throw new \Exception("$columnName column not defined in $this->tableName model");
    }

    protected function addColumnValidation($dbColumn, $validationType, $validationValue)
    {
        $dbColumn->addValidation($validationType, $validationValue);
    }

    protected function addColumnValidationByName($columnName, $validationType, $validationValue)
    {
        $this->addColumnValidation($this->getColumn($columnName), $validationType, $validationValue);
    }

    protected function removeColumnValidation($dbColumn, $validationType)
    {
        $dbColumn->removeValidation($validationType);
    }

    protected function removeColumnValidationByName($columnName, $validationType)
    {
        $this->removeColumnValidation($this->getColumn($columnName), $validationType);
    }

    /**
     * @param array $columnValues
     * @return bool
     * validate only the columns for which values are received
     * create sub-class and override if table-specific validation necessary
     * note, this could be changed to public if there's reason to validate independently of insert/update
     * $currentColumnValues are passed in for update validation
     * note, if extra columnValues are passed in, ie they don't exist in columns for this table, they are ignored
     */
    protected function validate(&$columnValues, $skipColumnNames = null, &$currentColumnValues = null)
    {
        //printPreArray($columnValues);die();
        $valid = true;
        foreach ($this->columns as $c) {
            $cName = $c->getName();
            if (is_array($skipColumnNames) && !in_array($cName, $skipColumnNames)) {
                // make sure all required columns exist in the argument. if not, set value to null to cause Required error
                if ($c->isRequired() && !array_key_exists($cName, $columnValues)) {
                    $columnValues[$cName] = null;
                }
                if (array_key_exists($cName, $columnValues)) {
                    $currentValue = (!is_null($currentColumnValues) && array_key_exists($cName, $currentColumnValues)) ? $currentColumnValues[$cName] : false;
                    if (!$c->_validate($columnValues[$cName], $currentValue)) {
                        $valid = false;
                    }
                }
            }
        }
        return $valid;
    }

    public function getRowCountForColumnValue($columnName, $value)
    {
        if (!$this->getColumn($columnName)) {
            throw new \Exception("Invalid column name: $columnName for table: $this->tableName");
        }
        $q = new Database\QueryBuilder("SELECT * FROM $this->tableName WHERE $columnName = $1", $value);

        $res = $q->execute();
        return pg_num_rows($res);
    }

    public function doesColumnValueExist($columnName, $value)
    {
        return $this->getRowCountForColumnValue($columnName, $value) > 0;
    }

    private function handleBlankValue($column)
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

    private function addColumnsToBuilder($qb, &$columnValues, &$updateRow=null)
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

    public function selectRowByPrimaryKey($pkValue)
    {
        if (!$pkColumn = $this->getPrimaryKeyColumn()) {
            throw new \Exception("Primary Key Column does not exist for table ".$this->tableName);
        }
        $q = new Database\QueryBuilder("SELECT * FROM ".$this->tableName." WHERE $pkColumn = $1", $pkValue);

        return $q->execute();
    }

    /**
     * @param array $columnValues
     * @return bool
     */
    public function insert($columnValues)
    {
        if (!$this->allowInsert || !$this->validate($columnValues, array($this->primaryKeyColumn))) {
            throw new \Exception('Insert not allowed on table '.$this->tableName);
        }
        $ib = new Database\InsertBuilder($this->tableName);
        $this->addColumnsToBuilder($ib, $columnValues);
        return ($ib->execute()) ? true : false;
    }

    public function update(&$columnValues, $pkValue)
    {
        if (!$currentRes = $this->selectRowByPrimaryKey($pkValue)) {
            throw new \Exception("INVALID pkValue $pkValue passed");
        }
        $currentRow = pg_fetch_assoc($currentRes);
        if (!$this->isUpdateAllowed() || !$this->validate($columnValues, null, $currentRow)) {
            $updErrMsg = (!$this->isUpdateAllowed()) ? "Update Not Allowed" : "Error";
            throw new \Exception($updErrMsg);
        }
        $ub = new Database\UpdateBuilder($this->tableName, $this->getPrimaryKeyColumn(), $pkValue);
        $this->addColumnsToBuilder($ub, $columnValues, $currentRow);
        if (count($ub->args) == 0) {
            throw new \Exception("No changes made.");
        } elseif (!$res = $ub->execute()) {
            throw new \Exception("Query failed.");
        }
        return true;
    }

    public function delete($pkValue)
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
    public function select($columns = "*", $whereArr = [], $orderByOverride = null, $limitOverride = null)
    {
        $q = new Database\QueryBuilder("SELECT * FROM $this->tableName");
        if (count($whereArr) > 0) {
            $q->add(" WHERE ");
            $i = 0;
            foreach ($whereArr as $colName => $colValue) {
                if ($this->getColumn($colName)) {
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
        } elseif (\It_All\BoutiqueCommerce\Utilities\isInteger($limitOverride)) {
            $limit = "LIMIT $limitOverride";
        } else {
            $limit = "LIMIT $this->selectLimit";
        }
        $q->add(" ORDER BY $orderBy $limit");
        return $q->execute();
    }
}