<?php

namespace MF\Dbwatcher\Runner;

interface RunnerInterface
{
    /**
     * @param $watchedFilePath Path to file being watched
     * @param array $tasks Array of parsed tasks with rules
     */
    public function run($watchedFilePath, array $tasks);
}