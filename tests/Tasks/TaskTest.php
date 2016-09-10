<?php

namespace MF\Dbwatcher\Tests\Tasks;

use MF\Dbwatcher\Tasks\Task;
use MF\Dbwatcher\Tests\Mock\Actions\BlankAction;
use MF\Dbwatcher\Tests\Mock\Rules\BlankRule;

class TaskTest extends \PHPUnit_Framework_TestCase
{
    private $standardData = [
        'database' => 'cmsapp',
        'table' => 'cms_settings',
        'type' => 'update',
        'ts' => 1471783160,
        'xid' => 293,
        'commit' => true,
        'data' =>
            [
                'id' => 1,
                'path' => 'key1',
                'setting_vale' => 'fee',
            ],
        'old' =>
            [
                'setting_vale' => 'ree',
            ],
    ];

    /**
     * @covers Task::addRule
     */
    public function testAddRule()
    {
        $task = new Task();
        $blankRule = new BlankRule(true);

        $task->addRule($blankRule);

        $this->assertArraySubset([$blankRule], $task->getRules());
    }

    /**
     * @covers Task::addAction
     */
    public function testAddAction()
    {
        $task = new Task();
        $blankAction = new BlankAction();

        $task->addAction($blankAction);

        $this->assertArraySubset([$blankAction], $task->getActions());
    }

    /**
     * @covers Task::run
     */
    public function testRunSuccessful()
    {
        $task = new Task();
        $blankRule = new BlankRule(true);
        $blankAction = new BlankAction();
        $task->addRule($blankRule);
        $task->addAction($blankAction);

        $this->assertTrue($task->run($this->standardData));
        $this->assertTrue($blankAction->ran);
    }

    /**
     * @covers Task::run
     */
    public function testRunNotProcessed()
    {
        $task = new Task();
        $blankAction = new BlankAction();
        $task->addRule(new BlankRule(true))->addRule(new BlankRule(true))->addRule(new BlankRule(false));
        $task->addAction($blankAction);

        $this->assertFalse($task->run($this->standardData));
        $this->assertFalse($blankAction->ran);
    }

    /**
     * @covers Task::getMessageFromMaxwellData
     */
    public function testGetMessageFromMaxwellData()
    {
        date_default_timezone_set('Etc/GMT+0');

        $table = [
            [
                'input' => [
                    'database' => 'cmsapp',
                    'table' => 'cms_settings',
                    'type' => 'insert',
                    'ts' => 1471783160,
                    'xid' => 293,
                    'commit' => true,
                    'data' =>
                        [
                            'id' => 1,
                            'path' => 'key1',
                            'setting_vale' => 'fee',
                        ],
                ],
                'output' => 'Insert was ran inserting {"id":1,"path":"key1","setting_vale":"fee"} on Sun, 21 Aug 2016 12:39:20 +0000'
            ],
            [
                'input' => [
                    'database' => 'cmsapp',
                    'table' => 'cms_settings',
                    'type' => 'update',
                    'ts' => 1471783160,
                    'xid' => 293,
                    'commit' => true,
                    'data' =>
                        [
                            'id' => 1,
                            'path' => 'key1',
                            'setting_vale' => 'bar',
                        ],
                    'old' => ['setting_vale' => 'foo']
                ],
                'output' => 'Update was ran update {"id":1,"path":"key1","setting_vale":"bar"} from {"id":1,"path":"key1","setting_vale":"foo"} changing columns setting_vale on Sun, 21 Aug 2016 12:39:20 +0000',
            ],
            [
                'input' => [
                    'database' => 'cmsapp',
                    'table' => 'cms_settings',
                    'type' => 'delete',
                    'ts' => 1471783160,
                    'xid' => 293,
                    'commit' => true,
                    'data' =>
                        [
                            'id' => 1,
                            'path' => 'key1',
                            'setting_vale' => 'fee',
                        ],
                ],
                'output' => 'Delete was ran removing {"id":1,"path":"key1","setting_vale":"fee"} on Sun, 21 Aug 2016 12:39:20 +0000'
            ],
            [
                'input' => ['type'=>'bar'],
                'output' => 'Got {"type":"bar"}'
            ],
            [
                'input' => ['foo'=>'bar'],
                'output' => 'No query type data is set'
            ]
        ];

        foreach ($table as $data) {
            $this->assertSame($data['output'], Task::getMessageFromMaxwellData($data['input']));
        }

    }
}