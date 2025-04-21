<?php

use API\CheckPrice\Domain\GasStation\Repositories\GasStationRepositoryInterface;
use API\CheckPrice\Helper\PdoLogHandler;
use API\CheckPrice\Infra\Persistence\Sqlite\GasStationRepository;
use DI\ContainerBuilder;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        PDO::class => function (ContainerInterface $c) {
            $dbPath = __DIR__ . './../database/database.db';
            $dsn = 'sqlite:' . $dbPath;
    
            try {
                $pdo = new PDO($dsn);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
                $this->logger->error('Connection failed: ' . $e->getMessage());
                exit;
            }
    
            return $pdo;
        },
        LoggerInterface::class => function (ContainerInterface $c) {
            $loggerSettings = $c->get('settings')['logger'];
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            // $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']); // log em arquivo
            $handler = new PdoLogHandler($c->get(PDO::class), $loggerSettings['level']); // log em banco de dados
            $logger->pushHandler($handler);

            return $logger;
        },
        
        GasStationRepositoryInterface::class => function (ContainerInterface $c) {
            return new GasStationRepository($c->get(PDO::class));
        }
        
    ]);
    
};
