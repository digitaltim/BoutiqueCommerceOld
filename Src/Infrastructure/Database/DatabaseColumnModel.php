<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure\Database;

use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\QueryBuilder;

class DatabaseColumnModel
{
    /** @var  */
    private $dbTableModel;

    /** @var string */
    private $name;

    /** @var string postgres column type */
    private $type;

    /** @var bool */
    private $isNullable;

    /**
    * @var bool
    * true if column has a unique constraint
    */
    private $isUnique;

    /** @var can be string or null */
    private $defaultValue;

    /** @var string */
    private $characterMaximumLength;

    /** @var string */
    private $udtName;

    /** @var  array|null only applies to enum (USER-DEFINED) types */
    private $enumOptions;

    /** @var  array */
    private $validation;

    /** @var array http://www.postgresql.org/docs/9.4/static/datatype-numeric.html */
    private $numericTypes = array('smallint', 'integer', 'bigint', 'decimal', 'numeric', 'real', 'double precision', 'smallserial', 'serial', 'bigserial');

    function __construct(DatabaseTableModel $dbTableModel,  array $columnInfo)
    {
        $this->dbTableModel = $dbTableModel;
        $this->name = $columnInfo['column_name'];
        $this->type = $columnInfo['data_type'];
        $this->isNullable = $columnInfo['is_nullable'] == 'YES';
        $this->characterMaximumLength = $columnInfo['character_maximum_length'];
        $this->udtName = $columnInfo['udt_name'];
        $this->isUnique = $columnInfo['is_unique'];
        $this->setEnumOptions();
        $this->setDefaultValue($columnInfo['column_default']);
        $this->setValidation();
    }

    private function setValidation()
    {
        $this->validation = array();
        if (!$this->isNullable) {
            $this->addValidation('required', true);
        }
        switch ($this->type) {
            case 'numeric':
                $this->addValidation('numeric', true);
                break;
            case 'smallint':
            case 'bigint':
            case 'integer':
                $this->addValidation('integer', true);
                break;
            case 'date':
                $this->addValidation('date', true);
                break;
            case 'timestamp without time zone':
                $this->addValidation('timestamp', true);
                break;
            case 'boolean':
            case 'character':
            case 'character varying':
            case 'text' :
            case 'USER-DEFINED':
                break; // no default validation
            default:
                throw new \Exception("$this->type column type validation not defined, column $this->name");
        }
    }

    /** input can be null */
    public function setDefaultValue($columnDefault)
    {
        if (is_null($columnDefault)) {
            $this->defaultValue = null;
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
                    $this->defaultValue = $parseColDef[1];
                    break;
                default:
                    $this->defaultValue = $columnDefault;
            }
        }
    }

    public function setEnumOptions()
    {
        if ($this->type != 'USER-DEFINED') {
            $this->enumOptions = null;
        } else {
            $this->enumOptions = [];
            $q = new QueryBuilder("SELECT e.enumlabel as enum_value FROM pg_type t JOIN pg_enum e on t.oid = e.enumtypid JOIN pg_catalog.pg_namespace n ON n.oid = t.typnamespace WHERE t.typname = $1", $this->udtName);
            $qResult = $q->execute();
            if (pg_num_rows($qResult) == 0) {
                throw new \Exception("No values for enum field $this->name");
            }
            while ($row = pg_fetch_assoc($qResult)) {
                $this->enumOptions[] = $row['enum_value'];
            }
        }
    }

    public function addValidation(string $validationType, $validationValue)
    {
        $this->validation[$validationType] = $validationValue;
    }

    public function removeValidation(string $validationType)
    {
        if (isset($this->validation[$validationType])) {
            unset($this->validation[$validationType]);
        }
    }

    /** true if validation type is set */
    public function validationTypeExists($vType):bool
    {
        return array_key_exists($vType, $this->validation);
    }

    /** true if required validation is set */
    public function isRequired(): bool
    {
        return $this->validationTypeExists('required');
    }

    /**
     * @return bool
     * not to be confused with column type numeric, it can be any type in $this->numericTypes
     */
    public function isNumericType(): bool
    {
        return in_array($this->type, $this->numericTypes);
    }

    public function isPrimaryKey()
    {
        return $this->name == $this->dbTableModel->getPrimaryKeyColumnName();
    }

    public function isBoolean(): bool
    {
        return ($this->type == 'boolean');
    }

    // getters

    public function getValidation(): array
    {
        return $this->validation;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getIsNullable(): bool
    {
        return $this->isNullable;
    }

    public function getIsUnique(): bool
    {
        return $this->isUnique;
    }

    public function getIsBlankable(): bool
    {
        return $this->isBlankable;
    }

    public function getCharacterMaximumLength()
    {
        return $this->characterMaximumLength;
    }

    public function getUdtName(): string
    {
        return $this->udtName;
    }

    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    public function getEnumOptions()
    {
        return $this->enumOptions;
    }
}
