<?php
namespace It_All\BoutiqueCommerce\UI;

use It_All\BoutiqueCommerce\Models\DbTable;

/**
 * Class UiRsDbTable
 * makes html table from recordset from 1 particular db table
 */
class UiRsDbTable extends UiRsTable
{
    private $dbTableModel;

    function __construct(DbTable $dbTableModel, $outputColumns = [])
    {
        $this->dbTableModel = $dbTableModel;
        // create output columns
        foreach ($this->dbTableModel->getColumns() as $column) {
            $columnName = $column->getName();
            $outputColumns[$columnName] = [];
            if ($this->isUpdateColumn($columnName)) {
                $outputColumns[$columnName]['link'] = 'VALUE'; // todo fix
            }
        }
        parent::__construct($outputColumns);
    }

    private function isUpdateColumn($columnName)
    {
        return ($columnName == $this->dbTableModel->getPrimaryKeyColumn() && $this->dbTableModel->isUpdateAllowed());
    }

    // todo add $this->links functionality like parent tableRow if nec
    protected function tableRowREMOVE(array $row, $type = 'body')
    {
        $tableName = $this->dbTableModel->getTableName();
        if ($type == 'body') {
            $cellTag = 'td';
            $cellValuePointer = 'v'; // for array value
        } else {
            $cellTag = 'th';
            $cellValuePointer = 'i'; // for array index
        }
        $html = "<tr>";
        foreach ($row as $i => $v) {
            $cellValue = $$cellValuePointer;
            // add update link for primary key column values
            if ($this->isUpdateCell($type, $i)) {
                $cellValue = "<a href='update.php?t=$tableName&amp;i=" . $row['id'] . "' title='edit'>$cellValue</a>";
            }
            $html .= "<$cellTag>" . $cellValue . "</$cellTag>";
        }
        // add on delete column if nec
        if ($this->dbTableModel->isDeleteAllowed()) {
            $html .= "<$cellTag>";
            if ($type == 'body') {
                $html .= "<a href='" . $_SERVER['SCRIPT_NAME'] . "?t=$tableName&amp;r=" . $row['id'] . "' title='delete' onclick='if(!confirm(\"DELETE id " . $row['id'] . "?\")){return false;}'>";
            }
            $html .= "X";
            if ($type == 'body') {
                $html .= "</a>";
            }
            $html .= "</$cellTag>";

        }
        $html .= "</tr>";
        return $html;
    }

}