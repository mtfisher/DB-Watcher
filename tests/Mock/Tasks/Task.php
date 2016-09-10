<?php
namespace MF\Dbwatcher\Tests\Mock\Tasks;

use MF\Dbwatcher\Tasks\TaskInterface;

class Task implements TaskInterface
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Add rule to a task
     * @param \MF\Dbwatcher\Rules\Rule $rule
     * @return $this
     */
    public function addRule(\MF\Dbwatcher\Rules\Rule $rule)
    {

    }

    /**
     * Add a action to a task to be ran if all rules validate
     * @param \MF\Dbwatcher\Actions\Action $action
     * @return $this
     */
    public function addAction(\MF\Dbwatcher\Actions\Action $action)
    {

    }

    /**
     * Check if message passes rules and then run the actions
     * @param array $message the message from maxwell
     * @return bool
     */
    public function run(array $message)
    {

    }
}