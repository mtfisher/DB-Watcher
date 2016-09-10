<?php

namespace MF\Dbwatcher\Tests\Actions;

use MF\Dbwatcher\Actions\Factory;
use MF\Dbwatcher\Tests\Mock\Actions\BlankLogger;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Factory::create
     */
    public function testCreateFullPath()
    {
        $container = \DI\ContainerBuilder::buildDevContainer();
        $factory = new Factory($container);

        $action = $factory->create("\\MF\\Dbwatcher\\Tests\\Mock\\Actions\\BlankAction", []);

        $this->assertInstanceOf("\\MF\\Dbwatcher\\Tests\\Mock\\Actions\\BlankAction", $action);
    }

    /**
     * @covers Factory::create
     */
    public function testCreateDefaultPath()
    {
        $container = \DI\ContainerBuilder::buildDevContainer();

        /**
         * Set Blank logger for Psr\Log\LoggerInterface
         */
        $container->set("Psr\\Log\\LoggerInterface", new BlankLogger());

        $factory = new Factory($container);

        $action = $factory->create('Logger', ['level' => 'info']);

        $this->assertInstanceOf("\\MF\\Dbwatcher\\Actions\\Logger", $action);
    }

    /**
     * @covers Factory::create
     * @expectedException \MF\Dbwatcher\Actions\Exceptions\FactoryClassNotFound
     */
    public function testCreateNotFoundFullPath()
    {
        $container = \DI\ContainerBuilder::buildDevContainer();
        $factory = new Factory($container);

        $action = $factory->create("\\MF\\Dbwatcher\\Tests\\Mock\\Actions\\FakeAction", []);
    }

    /**
     * @covers Factory::create
     * @expectedException \MF\Dbwatcher\Actions\Exceptions\FactoryClassNotFound
     */
    public function testCreateNotFoundDefaultPath()
    {
        $container = \DI\ContainerBuilder::buildDevContainer();
        $factory = new Factory($container);

        $action = $factory->create("FakeAction", []);
    }

    /**
     * @covers Factory::create
     * @expectedException \MF\Dbwatcher\Actions\Exceptions\FactoryClassNotValid
     */
    public function testCreateNotValidClass()
    {
        $container = \DI\ContainerBuilder::buildDevContainer();
        $factory = new Factory($container);

        $action = $factory->create("\\MF\\Dbwatcher\\Tests\\Mock\\Actions\\NonAction", []);
    }
}