<?php

namespace MF\Dbwatcher\Tasks;

class Task implements TaskInterface
{
    const DML_INSERT = "INSERT";
    const DML_UPDATE = "UPDATE";
    const DML_DELETE = "DELETE";

    /**
     * Array for rules
     * @var array
     */
    private $rules = [];

    private $actions = [];

    /**
     * Add rule to a task
     * @param \MF\Dbwatcher\Rules\Rule $rule
     * @return $this
     */
    public function addRule(\MF\Dbwatcher\Rules\Rule $rule)
    {
        $this->rules[] = $rule;
        return $this;
    }

    /**
     * Get the rules
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Add a action to a task to be ran if all rules validate
     * @param \MF\Dbwatcher\Actions\Action $action
     * @return $this
     */
    public function addAction(\MF\Dbwatcher\Actions\Action $action)
    {
        $this->actions[] = $action;
        return $this;
    }

    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Check if message passes rules and then run the actions
     * @param array $message the message from maxwell
     * @return bool
     */
    public function run(array $message)
    {
        if(empty($this->rules)) {
            return false;
        }

        foreach ($this->getRules() as $rule) {
            /**
             * @var \MF\Dbwatcher\Rules\Rule $rule
             */
            if(!$rule->validate($message)) {
                return false;
            }
        }

        $messageString = self::getMessageFromMaxwellData($message);

        foreach ($this->getActions() as $action) {
            /**
             * @var \MF\Dbwatcher\Actions\Action $action
             */
            $action->run($messageString);
        }

        return true;
    }

    public static function getMessageFromMaxwellData(array $message)
    {
        if(array_key_exists('type', $message)) {
            switch (preg_replace('/\s+/', '', strtoupper($message['type']))) {
                case self::DML_INSERT:
                    return sprintf(
                        'Insert was ran inserting %1$s on %2$s',
                        json_encode($message['data']),
                        self::formatTimeStamp($message['ts'])
                    );
                    break;
                case self::DML_UPDATE:
                    return sprintf(
                        'Update was ran update %1$s from %2$s changing columns %3$s on %4$s',
                        json_encode($message['data']),
                        json_encode(array_merge($message['data'], $message['old'])),
                        implode(', ', array_keys($message['old'])),
                        self::formatTimeStamp($message['ts'])
                    );
                    break;
                case self::DML_DELETE:
                    return sprintf(
                        'Delete was ran removing %1$s on %2$s',
                        json_encode($message['data']),
                        self::formatTimeStamp($message['ts'])
                    );
                    break;
                default:
                    return sprintf('Got %1$s', json_encode($message));
            }
        }

        return 'No query type data is set';
    }

    private static function formatTimeStamp($timestamp)
    {
        return date(DATE_RFC2822, $timestamp);
    }

}