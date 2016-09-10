
<?php
use Psr\Log\LoggerInterface;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\NativeMailerHandler;

## Use this section for cli output to smoke test
return [
    LoggerInterface::class => function (){
        $log = new Logger('name');
        $log->pushHandler(new StreamHandler('php://stdout', Logger::INFO));
        return $log;
    },
];

## Use this section for email and logging file
/**
return [
    LoggerInterface::class => function (){
        $log = new Logger('name');
        $log->pushHandler(new StreamHandler('/var/log/', Logger::INFO));
        $log->pushHandler(
            new NativeMailerHandler(
                'to@email.com',
                'Alert Subject',
                'from@email.com',
                Logger::WARNING
            );
        );
        return $log;
    },
];
**/