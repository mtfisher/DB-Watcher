<?php
namespace MF\Dbwatcher\Actions;

class Logger implements Action
{
    const LOOGER_LEVEL_ARGNAME = 'level';

    const VALID_LEVEL_ALERT = \Psr\Log\LogLevel::ALERT;
    const VALID_LEVEL_WARNING = \Psr\Log\LogLevel::WARNING;
    const VALID_LEVEL_INFO = \Psr\Log\LogLevel::INFO;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    private $level;

    public function __construct(\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param array $args
     * @return $this
     */
    public function setActionArgs(array $args)
    {
        if(!array_key_exists(self::LOOGER_LEVEL_ARGNAME, $args)) {
            throw new Exceptions\LoggerLevelNotSet("Logger Level not set");
        }

        $level = preg_replace('/\s+/', '',strtolower($args[self::LOOGER_LEVEL_ARGNAME]));

        switch ($level){
            case self::VALID_LEVEL_ALERT:
            case self::VALID_LEVEL_WARNING:
            case self::VALID_LEVEL_INFO:
                $this->level = $level;
                break;
            default:
                throw new Exceptions\LoggerLevelNotValid("Log level not valid must be info,warning or alert");
        }

        return $this;
    }

    public function getLogLevel()
    {
        return $this->level;
    }

    /**
     * @param $message
     */
    public function run($message)
    {
        switch ($this->level) {
            case self::VALID_LEVEL_ALERT:
                $this->logger->alert($message);
                break;
            case self::VALID_LEVEL_WARNING:
                $this->logger->warning($message);
                break;
            case self::VALID_LEVEL_INFO:
                $this->logger->info($message);
                break;
        }
    }
}