<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Models;

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

    /** @var string */
    private $defaultValue;

    /** @var string */
    private $characterMaximumLength;

    /** @var string */
    private $udtName;

    /** @var  array */
    private $validation;

    /** @var string or null */
    private $validationError;

    /** @var array http://www.postgresql.org/docs/9.4/static/datatype-numeric.html */
    private $numericTypes = array('smallint', 'integer', 'bigint', 'decimal', 'numeric', 'real', 'double precision', 'smallserial', 'serial', 'bigserial');

    function __construct(DbTable $dbTableModel,  array $columnInfo)
    {
        $this->dbTableModel = $dbTableModel;
        $this->name = $columnInfo['column_name'];
        $this->type = $columnInfo['data_type'];
        $this->isNullable  = $columnInfo['is_nullable'] == 'YES';
        $this->defaultValue = $columnInfo['column_default'];
        $this->characterMaximumLength = $columnInfo['character_maximum_length'];
        $this->udtName = $columnInfo['udt_name'];
        $this->validation = array();
        $this->setValidation();
        $this->validationError = null;
    }

    private function setValidation()
    {
        if (!$this->isNullable && ($this->getDefaultValue() === false || strlen($this->getDefaultValue()) > 0) ) {
            // otherwise blank is allowed
            $this->addValidation('required');
        }
        switch ($this->type) {
            case 'numeric':
                $this->addValidation('numeric');
                break;
            case 'smallint':
            case 'bigint':
            case 'integer':
                $this->addValidation('integer');
                break;
            case 'date':
                $this->addValidation('date');
                break;
            case 'timestamp without time zone':
                $this->addValidation('timestamp');
                break;
            case 'boolean':
            case 'character':
            case 'character varying':
            case 'text' :
            case 'USER-DEFINED':
                break; // no validation
            default:
                trigger_error("$this->type column type validation not defined, column $this->name");
        }
    }

    public function getDefaultValue()
    {
        if ($this->defaultValue === null) {
            return false;
        }
        switch ($this->type) {
            case 'character':
            case 'character varying':
            case 'text':
                // formatted like 'default'::text
            case 'USER-DEFINED':
                // formatted like 'default'::tableName_columnName
                // parse out default
                $parseColDef = explode("'", $this->defaultValue);
                return $parseColDef[1];
                break;
            default:
                return $this->defaultValue;
        }
    }

    public function _validate($value, $currentValue = false): bool
    {
        if ($this->isNullable && (is_null($value) || strlen($value) == 0)) {
            return true;
        }
        foreach ($this->validation as $vType => $vValue) {
            switch ($vType) {
                case 'required':
                    if (\It_All\BoutiqueCommerce\Utilities\isBlankOrNull($value)) {
                        $this->validationError = 'Required';
                        return false;
                    }
                    break;
                case 'unique':
                    if ($currentValue === false || $value != $currentValue) {
                        // checking new field value or changed field value
                        //die("value: $value currentValue: $currentValue");
                        if ($this->dbTableModel->doesColumnValueExist($this->name, $value)) {
                            $this->validationError = 'Already Exists';
                            return false;
                        }
                    }
                    break;
                case 'integer':
                    if (!\It_All\BoutiqueCommerce\Utilities\isInteger($value)) {
                        $this->validationError = 'Must be Integer';
                        return false;
                    }
                    break;
                case 'numeric':
                    if (!\It_All\BoutiqueCommerce\Utilities\is_numeric($value)) {
                        $this->validationError = 'Must be Numeric';
                        return false;
                    }
                    break;
                case 'numeric_positive':
                    if (!\It_All\BoutiqueCommerce\Utilities\isNumericPositive($value)) {
                        $this->validationError = 'Must be Numeric Positive';
                        return false;
                    }
                    break;
                case 'date':
                    if (!\It_All\BoutiqueCommerce\Utilities\isDbDate($value)) {
                        $this->validationError = 'Invalid Date';
                        return false;
                    }
                    break;
                case 'timestamp':
                    if (!\It_All\BoutiqueCommerce\Utilities\isDbTimestamp($value)) {
                        $this->validationError = 'Invalid Timestamp';
                        return false;
                    }
                    break;
                default:
                    trigger_error("validation for $vType undefined", E_USER_ERROR); // exits
            }
        }
        return true; // no error
    }

    public function addValidation(string $validationType, string $validationValue = null)
    {
        $this->validation[$validationType] = $validationValue;
    }

    public function removeValidation(string $validationType)
    {
        if (isset($this->validation[$validationType])) {
            unset($this->validation[$validationType]);
        }
    }

    public function getValidation(): array
    {
        return $this->validation;
    }

    public function getValidationError()
    {
        return ($this->validationError === null) ? false : $this->validationError;
    }

    public function setValidationError($errorMsg)
    {
        $this->validationError = $errorMsg;
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

    public function isPrimaryKey()
    {
        return $this->name == $this->dbTableModel->getPrimaryKeyColumn();
    }
}
