<?php

namespace MF\Dbwatcher\Tests\Tasks;

use MF\Dbwatcher\Tasks\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Parser::__construct
     * @expectedException \MF\Dbwatcher\Tasks\Exceptions\ParserFileCouldNotBeFound
     */
    public function testConstructorThrowsFileNotFound()
    {
        $parser = new Parser('/tmp/FileNotFOund.yaml', new \MF\Dbwatcher\Tests\Mock\Tasks\Factory());
    }

    /**
     * @covers Parser::parse
     */
    public function testParse()
    {
        $filePath = realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'test.yaml';
        $parser = new Parser($filePath, new \MF\Dbwatcher\Tests\Mock\Tasks\Factory());
        $tasks = $parser->parse();
        $this->assertSame(2, count($tasks));

        $expectedReturn = [
            [
                'rules' =>
                    [
                        'DatabaseIs' =>
                            [
                                'database' => 'cmsapp',
                            ],
                        'DmlIs' =>
                            [
                                'dml' => 'update',
                            ],
                        'TableIs' =>
                            [
                                'table' => 'config',
                            ],
                    ],
                'actions' =>
                    [
                        'Logger' =>
                            [
                                'level' => 'warning',
                            ],
                    ],
            ],
            [
                'rules' =>
                    [
                        'DatabaseIs' =>
                            [
                                'database' => 'cmsapp',
                            ],
                        'DmlIs' =>
                            [
                                'dml' => 'delete',
                            ],
                        'TableIs' =>
                            [
                                'table' => 'config',
                            ],
                    ],
                'actions' =>
                    [
                        'Logger' =>
                            [
                                'level' => 'alert',
                            ],
                    ],
            ]
        ];

        foreach ($tasks as $pos => $task)
        {
            /**
             * @var \MF\Dbwatcher\Tests\Mock\Tasks\Task $task
             */
            $this->assertSame($expectedReturn[$pos], $task->data);
        }
    }
}