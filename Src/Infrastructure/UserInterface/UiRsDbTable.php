<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure\UserInterface;

use It_All\BoutiqueCommerce\Src\Infrastructure\Database\DbTable;

/**
 * Class UiRsDbTable
 * makes html table from recordset from 1 particular db table
 */
class UiRsDbTable extends UiRsTable
{
    protected $dbTableModel;

    function __construct(DbTable $dbTableModel, $outputColumns = [])
    {
        $this->dbTableModel = $dbTableModel;
        // create output columns
        foreach ($this->dbTableModel->getColumns() as $column) {
            $columnName = $column->getName();
            $outputColumns[$columnName] = [];
            if ($this->isUpdateColumn($columnName)) {
                $outputColumns[$columnName]['link'] = [
                    'title' => 'update',
                    'href' => $_SERVER['REQUEST_URI'].'/VALUE',
                    'text' => 'VALUE',
                    'target' => '',
                    'onclick' => ''
                ];
            }
        }
        $addDeleteCell = false;
        if ($this->dbTableModel->isDeleteAllowed()) {
            $addDeleteCell = true;
            $outputColumns['X']['link'] = [
                'title' => 'delete',
                'href' => $_SERVER['REQUEST_URI'].'/delete/VALUE',
                'valueKey' => $this->dbTableModel->getPrimaryKeyColumn(),
                'text' => 'X',
                'target' => '',
                'onclick' => ''
            ];
        }
        parent::__construct($outputColumns, $addDeleteCell, $this->dbTableModel->getPrimaryKeyColumn());
    }

    private function isUpdateColumn($columnName)
    {
        return ($columnName == $this->dbTableModel->getPrimaryKeyColumn() && $this->dbTableModel->isUpdateAllowed());
    }
}
