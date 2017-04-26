<?php
namespace It_All\BoutiqueCommerce\UI;

/**
 * Class UiRsDbTable
 * makes html table from recordset from 1 particular db table
 */
class UiRsDbTable extends UiRsTable
{
    private $dbTableModel;

    function __construct($dbTableModel, $outputColumns = [])
    {
        $this->dbTableModel = $dbTableModel;
        parent::__construct($outputColumns);
    }

    private function isUpdateCell($rowType, $cellColumn)
    {
        return ($rowType == 'body' && $cellColumn == $this->dbTableModel->getPrimaryKeyColumn() && $this->dbTableModel->isUpdateAllowed());
    }

    // todo add $this->links functionality like parent tableRow if nec
    private function tableRow(array $row, $type = 'body')
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

    protected function tableHeaderRow($row)
    {
        return $this->tableRow($row, 'header');
    }

    protected function tableBodyRow($row)
    {
        return $this->tableRow($row);
    }
}