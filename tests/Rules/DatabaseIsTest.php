<?php

namespace MF\Dbwatcher\Tests\Rules;

class DatabaseIsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \MF\Dbwatcher\Rules\DatabaseIs::setConditions
     */
    public function testSetConditions()
    {
        $condition = [\MF\Dbwatcher\Rules\DatabaseIs::DATABASE_NAME_FIELD  => 'foo'];

        $rule = new \MF\Dbwatcher\Rules\DatabaseIs();
        $rule->setConditions($condition);

        $this->assertSame(
            $condition[\MF\Dbwatcher\Rules\DatabaseIs::DATABASE_NAME_FIELD],
            $rule->getDbName(),
            "Database name was not set"
            );
    }

    /**
     * @expectedException \MF\Dbwatcher\Rules\Exceptions\DatabaseIsNotDatabaseGiven
     */
    public function testSetConditionsBlankException()
    {
        $rule = new \MF\Dbwatcher\Rules\DatabaseIs();
        $rule->setConditions([]);
    }

    /**
     * @expectedException \MF\Dbwatcher\Rules\Exceptions\DatabaseIsNotDatabaseGiven
     */
    public function testSetConditionsException()
    {
        $rule = new \MF\Dbwatcher\Rules\DatabaseIs();
        $rule->setConditions(['foo'=>'foo']);
    }

    /**
     * Test validation via a table test testing several conditions
     */
    public function testTableValidate()
    {

        $testCases = [
            [
                'data' => [
                    'database' => 'cmsapp',
                    'table' => 'cms_settings',
                    'type' => 'update',
                    'ts' => 1471783160,
                    'xid' => 293,
                    'commit' => true,
                    'data' =>
                        array (
                            'id' => 1,
                            'path' => 'key1',
                            'setting_vale' => 'fee',
                        ),
                    'old' =>
                        array (
                            'setting_vale' => 'ree',
                        ),
                ],
                'expected' => true,
                'message' => 'Expected validation to pass'
            ],
            [
                'data' => [
                    'database' => 'appcms',
                    'table' => 'cms_settings',
                    'type' => 'update',
                    'ts' => 1471783160,
                    'xid' => 293,
                    'commit' => true,
                    'data' =>
                        array (
                            'id' => 1,
                            'path' => 'key1',
                            'setting_vale' => 'fee',
                        ),
                    'old' =>
                        array (
                            'setting_vale' => 'ree',
                        ),
                ],
                'expected' => false,
                'message' => 'Expected validation to fail'
            ],
            [
                'data' => [
                    'table' => 'cms_settings',
                    'type' => 'update',
                    'ts' => 1471783160,
                    'xid' => 293,
                    'commit' => true,
                    'data' =>
                        array (
                            'id' => 1,
                            'path' => 'key1',
                            'setting_vale' => 'fee',
                        ),
                    'old' =>
                        array (
                            'setting_vale' => 'ree',
                        ),
                ],
                'expected' => false,
                'message' => 'Expected validation to fail as database is not in the maxwell data'
            ],
        ];

        $condition = [\MF\Dbwatcher\Rules\DatabaseIs::DATABASE_NAME_FIELD  => 'cmsapp'];

        $rule = new \MF\Dbwatcher\Rules\DatabaseIs();
        $rule->setConditions($condition);

        foreach($testCases as $testCase) {
            $this->assertEquals(
                $testCase['expected'],
                $rule->validate($testCase['data']),
                $testCase['message']
            );
        }

        //ok now test with no condition set which should return false
        $rule = new \MF\Dbwatcher\Rules\DatabaseIs();

        foreach($testCases as $testCase) {
            $this->assertSame(
                false,
                $rule->validate($testCase['data']),
                'No condition is set and got a true response when response should be false'
            );
        }
    }

}