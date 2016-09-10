<?php
namespace MF\Dbwatcher\Actions;

interface Action
{
    /**
     * @param array $args
     * @return $this
     */
    public function setActionArgs(array $args);

    /**
     * @param string $message
     */
    public function run($message);
}