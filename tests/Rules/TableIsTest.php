<?php

namespace MF\Dbwatcher\Tests\Rules;

class TableIsTest extends \PHPUnit_Framework_TestCase
{
    public function testSetConditions()
    {
        $condition = [\MF\Dbwatcher\Rules\TableIs::TABLE_NAME_FIELD  => 'foo'];

        $rule = new \MF\Dbwatcher\Rules\TableIs();
        $rule->setConditions($condition);

        $this->assertSame(
            $condition[\MF\Dbwatcher\Rules\TableIs::TABLE_NAME_FIELD],
            $rule->getTableName(),
            "Table name was not set"
        );
    }

    /**
     * @expectedException \MF\Dbwatcher\Rules\Exceptions\TableIsTableNameNotGiven
     */
    public function testSetConditionsBlankException()
    {
        $rule = new \MF\Dbwatcher\Rules\TableIs();
        $rule->setConditions([]);
    }

    /**
     * @expectedException \MF\Dbwatcher\Rules\Exceptions\TableIsTableNameNotGiven
     */
    public function testSetConditionsException()
    {
        $rule = new \MF\Dbwatcher\Rules\TableIs();
        $rule->setConditions(['foo'=>'foo']);
    }

    /**
     * Test validation via a table test testing several conditions
     */
    public function tableTestValidate()
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
                    'database' => 'cmsapp',
                    'table' => 'foo',
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
                    'database' => 'cmsapp',
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
                'message' => 'Expected validation to fail as table is not in the maxwell data'
            ],
        ];

        $condition = [\MF\Dbwatcher\Rules\TableIs::TABLE_NAME_FIELD  => 'cms_settings'];

        $rule = new \MF\Dbwatcher\Rules\TableIs();
        $rule->setConditions($condition);

        foreach($testCases as $testCase) {
            $this->assertSame(
                $testCase['expected'],
                $rule->validate($testCases['data']),
                $testCase['message']
            );
        }

        //ok test no rule given i should expect false for all

        $rule = new \MF\Dbwatcher\Rules\TableIs();

        foreach($testCases as $testCase) {
            $this->assertSame(
                false,
                $rule->validate($testCases['data']),
                'No condition is set and got a true response when response should be false'
            );
        }
    }
}