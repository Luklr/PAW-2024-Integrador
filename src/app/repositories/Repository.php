<?php

namespace Paw\App\Repositories;

use Paw\Core\Database\QueryBuilder;

abstract class Repository
{
    protected $model;
    protected static $instance = null;
    protected static $queryBuilder;

    private function __construct(QueryBuilder $queryBuilder)
    {
        self::$queryBuilder = $queryBuilder;
        $this->setModel();
    }

    public static function getInstance(QueryBuilder $queryBuilder = null)
    {
        if (self::$instance === null) {
            self::$instance = new static($queryBuilder);
        }
        return self::$instance;
    }

    // Esta la define cada subclase Repository
    abstract public function model();

    protected function setModel()
    {
        $this->model = $this->model();
    }

    protected function table()
    {
        $modelClass = $this->model;
        return $modelClass::$table;
    }

    public function getById($id)
    {
        $filter = "id = :id";
        $result = self::$queryBuilder->table($this->table())->select($filter, [':id' => $id]);
        if ($result) {
            return new $this->model($result[0]);
        }
        return null;
    }

    public function getAll()
    {
        $results = self::$queryBuilder->table($this->table())->select();
        $models = [];
        foreach ($results as $result) {
            $models[] = new $this->model($result);
        }
        return $models;
    }

    public function create(array $data)
    {
        $model = new $this->model($data);
        if ($model) {
            $id = self::$queryBuilder->table($this->table())->insert($model->toArray());
        }
        if ($id) {
            $model = $this->getById($id);
            return $model;
        }
    }

    public function update(int $id, array $data)
    {
        // Validar parámetros utilizando el modelo
        $class = $this->model;
        $model = new $class($data);
        if ($model) {
            $filter = "id = :id";
            $params = [':id' => $id];
            $success = self::$queryBuilder->table($this->table())->update($model->toArray(), $filter, $params);
        }
        if ($success) {
            $data = $this->getById($id);
            $model = new $this->model($data);
            return $model;
        }
        return null;
    }

    public function delete(int $id)
    {
        $filter = "id = :id";
        $params = [':id' => $id];
        return self::$queryBuilder->table($this->table())->delete($filter, $params);
    }
}
