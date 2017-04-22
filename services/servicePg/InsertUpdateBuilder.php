<?php
declare(strict_types=1);

namespace It_All\ServicePg;

abstract class InsertUpdateBuilder extends QueryBuilder
{
    public $dbTable;

    function __construct($pgConn, $dbTable)
    {
        $this->dbTable = $dbTable;
        parent::__construct($pgConn);
    }

    abstract public function addColumn(string $name, $value);

    abstract public function setSql();

    /**
     * executes query
     * @return recordset
     */
    public function execute()
    {
        if (!isset($this->sql)) {
            $this->setSql();
        }
        return parent::execute();
    }

}
