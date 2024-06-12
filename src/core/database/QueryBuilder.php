<?php

namespace Paw\Core\Database;

use Paw\Core\Traits\Loggeable;
use PDO;

class QueryBuilder {
    use Loggeable;
    
    private $pdo;
    private $table;
    private static $instance = null;

    private function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public static function getInstance(PDO $pdo = null) {
        if (self::$instance === null && $pdo !== null) {
            self::$instance = new QueryBuilder($pdo);
        }
        return self::$instance;
    }

    public function table(string $table) {
        $this->table = $table;
        return $this;
    }
    
    public function select(string $filter = null, array $params = [])
    {
        // $params es necesario para hacer el bind de los valores en el $filter
        $filterQuery = $filter ? "WHERE $filter" : "";

        $query = "SELECT * FROM {$this->table} {$filterQuery}";
        $sentencia = $this->pdo->prepare($query);

        // Bind parameters to the prepared statement
        foreach ($params as $key => $value) {
            $sentencia->bindValue($key, $value);
        }

        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute();
        return $sentencia->fetchAll();
    }

    public function selectPaginado(string $filter = null, array $params = [], int $limit = null, int $offset = null)
    {
        // $params es necesario para hacer el bind de los valores en el $filter
        $filterQuery = $filter ? "WHERE $filter" : "";

        $limitQuery = $limit ? "LIMIT $limit" : "";
        $offsetQuery = $offset ? "OFFSET $offset" : "";

        $query = "SELECT * FROM {$this->table} {$filterQuery}  {$limitQuery} {$offsetQuery}";
        $sentencia = $this->pdo->prepare($query);

        // Bind parameters to the prepared statement
        foreach ($params as $key => $value) {
            $sentencia->bindValue($key, $value);
        }

        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute();
        return $sentencia->fetchAll();
    }

    public function insert(array $data)
    {   
        // Si está seteado $id, lo elimino para que no se inserte en la BD porque será NULL
        if (is_null($data['id'])) {
            unset($data['id']);
        }

        $columnas = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_map(fn ($key) => ":$key", array_keys($data)));

        $query = "INSERT INTO {$this->table} ({$columnas}) VALUES ({$placeholders})";
        $sentencia = $this->pdo->prepare($query);

        //echo "<pre>";
        //var_dump($query);die;

        // Bind parameters to the prepared statement
        foreach ($data as $key => $value) {
            
            $sentencia->bindValue(":$key", $value);
        }

        $sentencia->execute();
        return $this->pdo->lastInsertId();
    }

    public function update(array $data, string $filter, array $params)
    {
        $setStr = implode(", ", array_map(fn ($key) => "$key = :$key", array_keys($data)));
        $query = "UPDATE {$this->table} SET {$setStr} WHERE $filter";
        $sentencia = $this->pdo->prepare($query);

        // Bind parameters to the prepared statement
        foreach ($data as $key => $value) { 
            $sentencia->bindValue(":$key", $value);     // bindea los valores para la parte SET
        }

        foreach ($params as $key => $value) {
            $sentencia->bindValue($key, $value);        // bindea los valores para la parte WHERE
        }

        return $sentencia->execute();
    }

    public function delete(string $filter, array $params)
    {
        $query = "DELETE FROM {$this->table} WHERE $filter";
        $sentencia = $this->pdo->prepare($query);

        // Bind parameters to the prepared statement
        foreach ($params as $key => $value) {
            $sentencia->bindValue($key, $value);
        }

        return $sentencia->execute();
    }

}