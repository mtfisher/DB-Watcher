<?php

namespace MF\Dbwatcher\Runner;

class DefaultRunner implements RunnerInterface
{
    public function run($watchedFilePath, array $tasks)
    {
        while(true) {

            if(!file_exists($watchedFilePath)) {
                throw new Exception\FileNotFound("{$watchedFilePath} was not found");
            }

            $file = fopen($watchedFilePath, 'r');
            while (($line = fgets($file)) !== false) {
                $json = json_decode($line, true);

                if(!empty($json)) {
                    foreach ($tasks as $task) {
                        /**
                         * @var \MF\Dbwatcher\Tasks\TaskInterface $task
                         */
                        $task->run($json);
                    }
                }
            }
            fclose($file);
        }
    }
}