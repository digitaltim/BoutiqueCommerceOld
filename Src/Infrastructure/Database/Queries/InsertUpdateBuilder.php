<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries;

abstract class InsertUpdateBuilder extends QueryBuilder
{
    protected $dbTable;

    protected $primaryKeyName;

    function __construct(string $dbTable)
    {
        $this->dbTable = $dbTable;
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

    public function setPrimaryKeyName(string $name)
    {
        $this->primaryKeyName = $name;
    }
}
