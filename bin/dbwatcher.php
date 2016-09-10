#!/usr/bin/env php
<?php

foreach ([__DIR__ . '/../../autoload.php', __DIR__ . '/../vendor/autoload.php', __DIR__ . '/vendor/autoload.php'] as $file) {
    if (file_exists($file)) {
        require_once $file;
    }
}

foreach ([__DIR__ . '/../../conf/bootstrap.php', __DIR__ . '/../conf/bootstrap.php', __DIR__ . '/conf/bootstrap.php'] as $file) {
    if(file_exists($file)) {
        require_once $file;
    }
}

$app = new \Silly\Edition\PhpDi\Application("DB Watcher", "0.1", $container);

$app->command('run [filepath]', function ($filepath, $taskSet, \Symfony\Component\Console\Output\OutputInterface $output) {
    try {
        $runner = new \MF\Dbwatcher\Runner\DefaultRunner();
        $runner->run($filepath, $taskSet);
    }catch (\Exception $e) {
        $output->writeln("Got the following error: {$e->getMessage()}");
    }
});

$app->run();