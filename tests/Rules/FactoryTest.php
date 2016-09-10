<?php

namespace MF\Dbwatcher\Tests\Rules;

use MF\Dbwatcher\Rules\Factory;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Factory::create
     */
    public function testCreateFullPath()
    {
        $container = \DI\ContainerBuilder::buildDevContainer();
        $factory = new Factory($container);

        $rule = $factory->create("\\MF\\Dbwatcher\\Tests\\Mock\\Rules\\BlankRule", []);

        $this->assertInstanceOf("\\MF\\Dbwatcher\\Tests\\Mock\\Rules\\BlankRule", $rule);
    }

    /**
     * @covers Factory::create
     */
    public function testCreateDefaultPath()
    {
        $container = \DI\ContainerBuilder::buildDevContainer();
        $factory = new Factory($container);

        /**
         * @var \MF\Dbwatcher\Rules\TableIs $rule
         */
        $rule = $factory->create("TableIs", ['table'=>'foo']);

        $this->assertInstanceOf("\\MF\\Dbwatcher\\Rules\\TableIs", $rule);
        $this->assertSame('foo', $rule->getTableName());
    }

    /**
     * @covers Factory::create
     * @expectedException \MF\Dbwatcher\Rules\Exceptions\FactoryClassNotFound
     */
    public function testCreateNotFoundFullPath()
    {
        $container = \DI\ContainerBuilder::buildDevContainer();
        $factory = new Factory($container);

        $action = $factory->create("\\MF\\Dbwatcher\\Tests\\Mock\\Rules\\FakeRules", []);
    }

    /**
     * @covers Factory::create
     * @expectedException \MF\Dbwatcher\Rules\Exceptions\FactoryClassNotFound
     */
    public function testCreateNotFoundDefaultPath()
    {
        $container = \DI\ContainerBuilder::buildDevContainer();
        $factory = new Factory($container);

        $action = $factory->create("FakeRules", []);
    }

    /**
     * @covers Factory::create
     * @expectedException \MF\Dbwatcher\Rules\Exceptions\FactoryClassNotValid
     */
    public function testCreateNotValidClass()
    {
        $container = \DI\ContainerBuilder::buildDevContainer();
        $factory = new Factory($container);

        $action = $factory->create("\\MF\\Dbwatcher\\Tests\\Mock\\Rules\\NonRule", []);
    }
}