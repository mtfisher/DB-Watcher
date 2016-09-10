<?php

namespace MF\Dbwatcher\Tests\Rules;

class DmlIsTest extends \PHPUnit_Framework_TestCase
{
    public function testTableSetConditions()
    {
        $table = [
            [
                'input' => \MF\Dbwatcher\Rules\DmlIs::DML_INSERT,
                'output' => \MF\Dbwatcher\Rules\DmlIs::DML_INSERT,
                'message' => "Insert was not set"
            ],
            [
                'input' => \MF\Dbwatcher\Rules\DmlIs::DML_UPDATE,
                'output' => \MF\Dbwatcher\Rules\DmlIs::DML_UPDATE,
                'message' => "update was not set"
            ],
            [
                'input' => \MF\Dbwatcher\Rules\DmlIs::DML_DELETE,
                'output' => \MF\Dbwatcher\Rules\DmlIs::DML_DELETE,
                'message' => "delete was not set"
            ],
            [
                'input' => strtolower(\MF\Dbwatcher\Rules\DmlIs::DML_INSERT),
                'output' => \MF\Dbwatcher\Rules\DmlIs::DML_INSERT,
                'message' => "insert lowercase was not set"
            ],
            [
                'input' => '     '.strtolower(\MF\Dbwatcher\Rules\DmlIs::DML_UPDATE).'   ',
                'output' => \MF\Dbwatcher\Rules\DmlIs::DML_UPDATE,
                'message' => "update space before was not set"
            ]
        ];

        $rule = new \MF\Dbwatcher\Rules\DmlIs();

        foreach ($table as $test) {
            $condition = [\MF\Dbwatcher\Rules\DmlIs::DML_NAME_FIELD  => $test['input']];

            $rule->setConditions($condition);

            $this->assertSame(
                $test['output'],
                $rule->getDmlType(),
                $test['message']
            );
        }

    }

    /**
     * @expectedException \MF\Dbwatcher\Rules\Exceptions\DmlIsDmlNotGiven
     */
    public function testSetConditionsBlankException()
    {
        $rule = new \MF\Dbwatcher\Rules\DmlIs();
        $rule->setConditions([]);
    }

    /**
     * @expectedException \MF\Dbwatcher\Rules\Exceptions\DmlIsDmlNotGiven
     */
    public function testSetConditionsException()
    {
        $rule = new \MF\Dbwatcher\Rules\DmlIs();
        $rule->setConditions(['foo'=>'Bar']);
    }

    /**
     * @expectedException \MF\Dbwatcher\Rules\Exceptions\DmlIsDmlInvalid
     */
    public function testSetConditionsInvalidDmlException()
    {
        $condition = [\MF\Dbwatcher\Rules\DmlIs::DML_NAME_FIELD  => 'bar'];
        $rule = new \MF\Dbwatcher\Rules\DmlIs();
        $rule->setConditions($condition);
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
                    'database' => 'cmsapp',
                    'table' => 'cms_settings',
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
                    'table' => 'cms_settings',
                    'type' => 'insert',
                    'ts' => 1471783160,
                    'xid' => 293,
                    'commit' => true,
                    'data' =>
                        array (
                            'id' => 1,
                            'path' => 'key1',
                            'setting_vale' => 'fee',
                        ),
                ],
                'expected' => false,
                'message' => 'Expected validation to fail as this is an insert not an update'
            ],
        ];

        $rule = new \MF\Dbwatcher\Rules\DmlIs();
        $condition = [\MF\Dbwatcher\Rules\DmlIs::DML_NAME_FIELD  => \MF\Dbwatcher\Rules\DmlIs::DML_UPDATE];
        $rule->setConditions($condition);

        foreach ($testCases as $test) {

            $this->assertSame(
                $test['expected'],
                $rule->validate($test['data']),
                $test['message']
            );
        }

        //test with a blank condition
        $rule = new \MF\Dbwatcher\Rules\DmlIs();

        foreach ($testCases as $test) {

            $this->assertSame(
                false,
                $rule->validate($test['data']),
                'No condition is set and I got a true return'
            );
        }
    }

}