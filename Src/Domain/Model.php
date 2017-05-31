<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain;

abstract class Model
{
    abstract protected function isInsertAllowed();

    abstract protected function isUpdateAllowed();

    abstract protected function isDeleteAllowed();

    private function addColumnsToBuilder($qb, $columnValues, &$updateRow=null)
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

    /**
     * @param array $columnValues
     * @return bool
     */
    public function insert($columnValues)
    {
        if (!$this->allowInsert || !$this->validate($columnValues, array($this->primaryKeyColumn))) {
            return false;
        }
        $ib = new InsertBuilder($this->tableName);
        $this->addColumnsToBuilder($ib, $columnValues);
        return ($ib->execute()) ? true : false;
    }
}
