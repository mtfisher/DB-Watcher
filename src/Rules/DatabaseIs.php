<?php
namespace MF\Dbwatcher\Rules;

class DatabaseIs implements Rule
{
    const DATABASE_NAME_FIELD = 'database';
    const MAXWELL_DATABASE_FIELD = 'database';

    protected $dbName;

    public function setConditions(array $conditionArgs)
    {
        if(!array_key_exists(self::DATABASE_NAME_FIELD, $conditionArgs)) {
            throw new Exceptions\DatabaseIsNotDatabaseGiven("No database as been specified please specify");
        }
        $this->dbName = $conditionArgs[self::DATABASE_NAME_FIELD];

        return $this;
    }

    public function getDbName()
    {
        return $this->dbName;
    }


    public function validate(array $maxwellData)
    {
        if(!array_key_exists(self::MAXWELL_DATABASE_FIELD, $maxwellData)) {
            return false;
        }

        return $maxwellData[self::MAXWELL_DATABASE_FIELD] === $this->dbName? true:false;
    }
}