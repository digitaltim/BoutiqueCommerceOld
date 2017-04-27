<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Utilities\Database;

class InsertBuilder extends InsertUpdateBuilder
{
    /** @var string */
    public $columns;

    /** @var string */
    public $values;

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
