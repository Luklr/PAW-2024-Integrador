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

require "routes.php";

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

# Inicializar los Repository singleton:
UserRepository::getInstance($querybuilder);

$request = new Request;

$router->setLogger($log);
/*
$whoops = new Run();
$whoops->pushHandler(new PrettyHandler());
$whoops->register();
*/