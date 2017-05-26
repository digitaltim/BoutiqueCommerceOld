<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure\Database;

use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\QueryBuilder;

class DbColumn
{
    /** @var  */
    private $dbTableModel;

    /** @var string */
    private $name;

    /** @var string */
    private $type;

    /** @var bool */
    private $isNullable;

    /**
     * @var bool
     * this is an assumption based on if not nullable and defaultValue is blank
     * it can be overriden using overrideIsBlankable
     * if a blank input is received for a nullable column it will be entered as null
     */
    private $isBlankable;

    /** @var string */
    private $defaultValue;

    /** @var string */
    private $characterMaximumLength;

    /** @var string */
    private $udtName;

    /**
     * @var bool
     * has a unique constraint
     */
    private $isUnique;

    /** @var array http://www.postgresql.org/docs/9.4/static/datatype-numeric.html */
    private $numericTypes = array('smallint', 'integer', 'bigint', 'decimal', 'numeric', 'real', 'double precision', 'smallserial', 'serial', 'bigserial');

    function __construct(DbTable $dbTableModel, array $columnInfo)
    {
        $this->dbTableModel = $dbTableModel;
        $this->name = $columnInfo['column_name'];
        $this->type = $columnInfo['data_type'];
        $this->isNullable  = $columnInfo['is_nullable'] == 'YES';
        $this->characterMaximumLength = $columnInfo['character_maximum_length'];
        $this->udtName = $columnInfo['udt_name'];
        $this->setDefaultValue($columnInfo['column_default']);
        $this->setIsUnique();
        $this->setIsBlankable();
    }

    private function setIsUnique()
    {
        $q = new QueryBuilder("SELECT column_name FROM INFORMATION_SCHEMA.constraint_column_usage ccu JOIN information_schema.table_constraints tc ON ccu.constraint_name = tc.constraint_name WHERE ccu.table_name = $1 and tc.table_name = $1 and constraint_type = 'UNIQUE'", $this->dbTableModel->getTableName());
        $this->isUnique = ($q->getOne()) ? true : false;
    }

    public function getIsUnique(): bool
    {
        return $this->isUnique;
    }

    private function setIsBlankable()
    {
        $this->isBlankable = (!$this->isNullable && $this->getDefaultValue() === '') ? true : false;
    }

    public function getIsBlankable(): bool
    {
        return $this->isBlankable;
    }

    public function overrideIsBlankable(bool $isBlankable)
    {
        $this->isBlankable = $isBlankable;
    }


    private function setDefaultValue($columnDefault)
    {
        if ($columnDefault === null) {
            $this->defaultValue = '';
        } else {
            switch ($this->type) {
                case 'character':
                case 'character varying':
                case 'text':
                    // formatted like 'default'::text
                case 'USER-DEFINED':
                    // formatted like 'default'::tableName_columnName
                    // parse out default
                    $parseColDef = explode("'", $columnDefault);
                    return $parseColDef[1];
                    break;
                default:
                    return $columnDefault;
            }
        }
    }

    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    public function isRequired(): bool
    {
        return !$this->isNullable;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return bool
     * not to be confused with column type numeric, it can be any type in $this->numericTypes
     */
    public function isNumericType(): bool
    {
        return in_array($this->type, $this->numericTypes);
    }

    public function isBoolean(): bool
    {
        return ($this->type == 'boolean');
    }

    public function getIsNullable(): bool
    {
        return $this->isNullable;
    }

    public function getCharacterMaximumLength()
    {
        return $this->characterMaximumLength;
    }

    public function getUdtName(): string
    {
        return $this->udtName;
    }

    public function isPrimaryKey(): bool
    {
        return $this->name == $this->dbTableModel->getPrimaryKeyColumn();
    }
}
