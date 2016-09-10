<?php

namespace MF\Dbwatcher\Tests\Actions;

use Psr\Log\LogLevel;

class LoggerTest extends \PHPUnit_Framework_TestCase
{
    public function testTableSetActionArgs()
    {
        $testData = [
            [
                'input' => [\MF\Dbwatcher\Actions\Logger::LOOGER_LEVEL_ARGNAME => 'alert'],
                'output' => LogLevel::ALERT
            ],
            [
                'input' => [\MF\Dbwatcher\Actions\Logger::LOOGER_LEVEL_ARGNAME => 'warning'],
                'output' => LogLevel::WARNING
            ],
            [
                'input' => [\MF\Dbwatcher\Actions\Logger::LOOGER_LEVEL_ARGNAME => 'info'],
                'output' => LogLevel::INFO
            ],
            [
                'input' => [\MF\Dbwatcher\Actions\Logger::LOOGER_LEVEL_ARGNAME => ' info '],
                'output' => LogLevel::INFO
            ],
            [
                'input' => [\MF\Dbwatcher\Actions\Logger::LOOGER_LEVEL_ARGNAME => 'WARNING'],
                'output' => LogLevel::WARNING
            ],
            [
                'input' => [\MF\Dbwatcher\Actions\Logger::LOOGER_LEVEL_ARGNAME => 'WaRnInG  '],
                'output' => LogLevel::WARNING
            ],
        ];

        $logger = new \MF\Dbwatcher\Actions\Logger(new \MF\Dbwatcher\Tests\Mock\Actions\BlankLogger());

        foreach ($testData as $data) {
            $logger->setActionArgs($data['input']);

            $this->assertSame($data['output'], $logger->getLogLevel(), "Got a mismatch");
        }
    }

    /**
     * @expectedException \MF\Dbwatcher\Actions\Exceptions\LoggerLevelNotSet
     */
    public function testSetActionArgsBlank()
    {
        $logger = new \MF\Dbwatcher\Actions\Logger( new \MF\Dbwatcher\Tests\Mock\Actions\BlankLogger());
        $logger->setActionArgs([]);
    }

    /**
     * @expectedException \MF\Dbwatcher\Actions\Exceptions\LoggerLevelNotSet
     */
    public function testSetActionArgsNotSet()
    {
        $logger = new \MF\Dbwatcher\Actions\Logger( new \MF\Dbwatcher\Tests\Mock\Actions\BlankLogger());
        $logger->setActionArgs(['foo'=>'bar']);
    }

    /**
     * @expectedException \MF\Dbwatcher\Actions\Exceptions\LoggerLevelNotValid
     */
    public function testSetActionArgsLevelNotValid()
    {
        $logger = new \MF\Dbwatcher\Actions\Logger( new \MF\Dbwatcher\Tests\Mock\Actions\BlankLogger());
        $logger->setActionArgs([\MF\Dbwatcher\Actions\Logger::LOOGER_LEVEL_ARGNAME => 'FOO']);
    }

    /**
     * Test the run method
     */
    public function testRun()
    {
        $loggerItem = new \MF\Dbwatcher\Tests\Mock\Actions\BlankLogger();

        $logger = new \MF\Dbwatcher\Actions\Logger($loggerItem);
        $logger->setActionArgs([\MF\Dbwatcher\Actions\Logger::LOOGER_LEVEL_ARGNAME => 'alert']);

        $logger->run('message 1');
        $this->assertSame('message 1', $loggerItem->alertStr);
        $this->assertEmpty($loggerItem->warningStr, "Warning was raised when alert should have");
        $this->assertEmpty($loggerItem->infoStr, "Info was raised when alert should have");
        $loggerItem->alertStr = '';

        $logger->setActionArgs([\MF\Dbwatcher\Actions\Logger::LOOGER_LEVEL_ARGNAME => 'warning']);

        $logger->run('message 1');
        $this->assertSame('message 1', $loggerItem->warningStr);
        $this->assertEmpty($loggerItem->infoStr, "Info was raised when warning should have");
        $this->assertEmpty($loggerItem->alertStr, "Alert was raised when warning should have");
        $loggerItem->warningStr = '';

        $logger->setActionArgs([\MF\Dbwatcher\Actions\Logger::LOOGER_LEVEL_ARGNAME => 'info']);

        $logger->run('message 1');
        $this->assertSame('message 1', $loggerItem->infoStr);
        $this->assertEmpty($loggerItem->warningStr, "Warning was raised when info should have");
        $this->assertEmpty($loggerItem->alertStr, "Alert was raised when info should have");
        $loggerItem->infoStr = '';
    }
}