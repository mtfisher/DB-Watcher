<?php
namespace MF\Dbwatcher\Rules;

use MF\Dbwatcher\Rules\Exceptions\DmlIsDmlInvalid;
use MF\Dbwatcher\Rules\Exceptions\DmlIsDmlNotGiven;

class DmlIs implements Rule
{
    const DML_INSERT = "INSERT";
    const DML_UPDATE = "UPDATE";
    const DML_DELETE = "DELETE";

    const DML_NAME_FIELD = 'dml';
    const MAXWELL_DML_FIELD = 'type';

    protected $dmlType;

    public function setConditions(array $conditionArgs)
    {
        if(!array_key_exists(self::DML_NAME_FIELD, $conditionArgs)) {
            throw new DmlIsDmlNotGiven('Query type was not given');
        }

        //sanitize the argument to remove spaces
        $value = preg_replace('/\s+/', '',strtoupper($conditionArgs[self::DML_NAME_FIELD]));

        switch ($value) {
            case self::DML_INSERT:
            case self::DML_UPDATE:
            case self::DML_DELETE:
                $this->dmlType = $value;
                break;
            default:
                throw new DmlIsDmlInvalid("Dml is invalid expect one of INSERT,UPDATE,DELETE Not {$value}");
                break;

        }

        return $this;

    }

    public function getDmlType()
    {
        return $this->dmlType;
    }


    public function validate(array $maxwellData)
    {
        if(!array_key_exists(self::MAXWELL_DML_FIELD, $maxwellData)) {
            return false;
        }

        //sanitize the type  to remove spaces
        $operation = preg_replace('/\s+/', '',strtoupper($maxwellData[self::MAXWELL_DML_FIELD]));

        return $operation === $this->dmlType?true:false;
    }
}