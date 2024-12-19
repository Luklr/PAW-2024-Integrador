<?php

namespace Paw\Core;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LoggerFactory
{
    private static $logger = null;

    public static function getLogger($config = null)
    {
        if (self::$logger === null) {
            $logger = new Logger('paw-app');
            $handler = new StreamHandler($config->get("LOG_PATH"));
            $handler->setLevel($config->get("LOG_LEVEL"));
            $logger->pushHandler($handler);
            self::$logger = $logger;
        }
        return self::$logger;
    }
}
