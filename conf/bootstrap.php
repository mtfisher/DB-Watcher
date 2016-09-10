<?php

$taskYaml = __DIR__.'/tasks.yaml';

if(!file_exists($taskYaml)) {
    echo "Could not find the tasks.yaml file inside the app conf directory";
    die(1);
}

$containerConf = __DIR__.'/container.conf.php';

if(!file_exists($containerConf)) {
    echo "Could not find the container.conf.php file inside the app conf directory\n";
    die(1);
}

$tasks = [];

$containerBuilder = new \DI\ContainerBuilder();
$container = $containerBuilder->addDefinitions($containerConf)->build();

try{
    $taskFactory = new \MF\Dbwatcher\Tasks\Factory(
        new \MF\Dbwatcher\Actions\Factory($container),
        new \MF\Dbwatcher\Rules\Factory($container));

    $parser = new \MF\Dbwatcher\Tasks\Parser($taskYaml, $taskFactory);

    $container->set('taskSet', $parser->parse());

}catch (\Exception $e) {
    echo "Got {$e->getMessage()}\n";
    die(1);
}