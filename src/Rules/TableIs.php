<?php
namespace MF\Dbwatcher\Rules;

class TableIs implements Rule
{
    const TABLE_NAME_FIELD = 'table';
    const MAXWELL_TABLE_FIELD = 'table';

    protected $table;


    public function setConditions(array $conditionArgs)
    {
        if(!array_key_exists(self::TABLE_NAME_FIELD, $conditionArgs)) {
            throw new Exceptions\TableIsTableNameNotGiven("No table as been specified please specify");
        }
        $this->table = $conditionArgs[self::TABLE_NAME_FIELD];

        return $this;
    }

    public function getTableName()
    {
        return $this->table;
    }


    public function validate(array $maxwellData)
    {
        if(!array_key_exists(self::MAXWELL_TABLE_FIELD, $maxwellData)) {
            return false;
        }

        return $maxwellData[self::MAXWELL_TABLE_FIELD] === $this->table? true:false;
    }
}