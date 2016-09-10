<?php

namespace MF\Dbwatcher\Tests\Mock\Actions;

use MF\Dbwatcher\Actions\Action;

class BlankAction implements Action
{
    /**
     * @var bool
     */
    public $ran = false;

    /**
     * @param array $args
     * @return $this
     */
    public function setActionArgs(array $args)
    {
        return $this;
    }

    /**
     * @param string $message
     */
    public function run($message)
    {
        $this->ran = true;
    }
}