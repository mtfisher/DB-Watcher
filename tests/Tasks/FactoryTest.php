<?php

namespace MF\Dbwatcher\Tests\Tasks;

use MF\Dbwatcher\Tasks\Factory;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Factory::create
     */
    public function testCreate()
    {
        $actionFactory = new \MF\Dbwatcher\Tests\Mock\Actions\Factory();
        $ruleFactory = new \MF\Dbwatcher\Tests\Mock\Rules\Factory();

        $factory = new Factory($actionFactory, $ruleFactory);

        $task = $factory->create([]);

        $this->assertInstanceOf('\\MF\\Dbwatcher\\Tasks\\Task', $task);
    }

    /**
     * @covers Factory::create
     */
    public function testCreateRule()
    {
        $args = [
            'key1' => 'val1',
            'key2' => 'val2'
        ];

        $actionFactory = new \MF\Dbwatcher\Tests\Mock\Actions\Factory();
        $ruleFactory = new \MF\Dbwatcher\Tests\Mock\Rules\Factory();

        $factory = new Factory($actionFactory, $ruleFactory);

        $task = $factory->create(
            [
                'rules' => [
                    '\\MF\\Dbwatcher\\Tests\\Mock\\Rules\\BlankRuleWithArgs' => $args
                ]
            ]
        );

        $this->assertNotEmpty($task->getRules(), 'An rule must be set');
        $this->assertSame('\\MF\\Dbwatcher\\Tests\\Mock\\Rules\\BlankRuleWithArgs', $ruleFactory->class);
        $this->assertSame($args, $ruleFactory->conditions);

    }

    /**
     * @covers Factory::create
     */
    public function testCreateAction()
    {
        $args = [
            'key3' => 'val1',
            'key4' => 'val2'
        ];

        $actionFactory = new \MF\Dbwatcher\Tests\Mock\Actions\Factory();
        $ruleFactory = new \MF\Dbwatcher\Tests\Mock\Rules\Factory();

        $factory = new Factory($actionFactory, $ruleFactory);

        $task = $factory->create(
            [
                'actions' => [
                    '\\MF\\Dbwatcher\\Tests\\Mock\\Actions\\BlankActionWithArgs' => $args
                ]
            ]
        );

        $this->assertNotEmpty($task->getActions(), 'An action must be set');
        $this->assertSame('\\MF\\Dbwatcher\\Tests\\Mock\\Actions\\BlankActionWithArgs', $actionFactory->class);
        $this->assertSame($args, $actionFactory->arguments);
    }
}