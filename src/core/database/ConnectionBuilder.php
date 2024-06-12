<?php

namespace Paw\Core\Database;

use PDO;
use PDOException;
use Paw\Core\Config;
use Paw\Core\Traits\Loggeable;

class ConnectionBuilder {
    use Loggeable;
    public function make(Config $config): PDO {
        try {
            $adapter = $config->get("DB_ADAPTER");
            $hostname = $config->get("DB_HOSTNAME");
            $dbname = $config->get("DB_DBNAME");
            $port = $config->get("DB_PORT");
            return new PDO(
                "{$adapter}:host={$hostname};port={$port};dbname={$dbname};",
                $config->get("DB_USERNAME"),
                $config->get("DB_PASSWORD"),
                [
                    "options" => [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                    ]
                ]
            );
        } catch (PDOException $e) {
            $this->logger->error("Internal Server Error", ["Error" => $e]);
            die("Error Interno - Consulte al administrador");
        }
    }
}