<?php
require __DIR__ . "/../vendor/autoload.php";
/*
use Whoops\Handler\PrettyHandler;
use Whoops\Run;
*/
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Dotenv\Dotenv;

use Paw\Core\Config;
use Paw\Core\Request;
use Paw\Core\Database\ConnectionBuilder;
use Paw\Core\Database\QueryBuilder;

use Paw\App\Repositories\UserRepository;
use Paw\App\Repositories\ComponentRepository;
use Paw\App\Repositories\AddressRepository;
use Paw\App\Repositories\BranchRepository;
use Paw\App\Repositories\OrderRepository;
use Paw\App\Repositories\NotificationRepository;
use Paw\App\Repositories\GeminiChatRepository;

require "routes.php";
require "helpers.php";

$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../');
$dotenv->load();

$config = new Config;

$log = new Logger('mvc-app');
$handler = new StreamHandler($config->get("LOG_PATH"));
$handler->setLevel($config->get("LOG_LEVEL"));
$log->pushHandler($handler);

$connectionBuilder = new ConnectionBuilder;
$connectionBuilder->setLogger($log);
$connection = $connectionBuilder->make($config);

$querybuilder = QueryBuilder::getInstance($connection);
$querybuilder->setLogger($log);

$querybuilder1 = QueryBuilder::getInstance($connection);
$querybuilder1->setLogger($log);

$querybuilder2 = QueryBuilder::getInstance($connection);
$querybuilder2->setLogger($log);

$querybuilder3 = QueryBuilder::getInstance($connection);
$querybuilder3->setLogger($log);

$querybuilder4 = QueryBuilder::getInstance($connection);
$querybuilder4->setLogger($log);

$querybuilder5 = QueryBuilder::getInstance($connection);
$querybuilder5->setLogger($log);

$querybuilder6 = QueryBuilder::getInstance($connection);
$querybuilder6->setLogger($log);

# Inicializar los Repository singleton:
UserRepository::getInstance($querybuilder);
ComponentRepository::getInstance($querybuilder1);
AddressRepository::getInstance($querybuilder2);
BranchRepository::getInstance($querybuilder3);
OrderRepository::getInstance($querybuilder4);
NotificationRepository::getInstance($querybuilder5);
GeminiChatRepository::getInstance($querybuilder6);

$request = new Request;

$router->setLogger($log);
/*
$whoops = new Run();
$whoops->pushHandler(new PrettyHandler());
$whoops->register();
*/