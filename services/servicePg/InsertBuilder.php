<?php
namespace It_All\ServicePg;

class InsertBuilder extends InsertUpdateBuilder
{
    public $columns;
    public $values;

    function __construct($pgConn, string $dbTable)
    {
        parent::__construct($pgConn, $dbTable);
    }

    /**
     * adds column to insert query
     * @param string $name
     * @param $value
    */
    public function addColumn(string $name, $value)
    {
        $this->args[] = $value;                
        if (strlen($this->columns) > 0) {
            $this->columns .= ", ";
        }
        $this->columns .= $name;
        if (strlen($this->values) > 0) {
            $this->values .= ", ";
        }
        $argNum = count($this->args);
        $this->values .= "$".$argNum;
    }

    /**
     * sets insert query
     */
    public function setSql()
    {
        $this->sql = "INSERT INTO $this->dbTable ($this->columns) VALUES($this->values)";
    }

}
