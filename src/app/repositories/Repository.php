<?php

namespace Paw\App\Repositories;

use Paw\Core\Database\QueryBuilder;
use Paw\Core\Model;

/**
 * The Repository class provides a base implementation for repositories.
 */
abstract class Repository
{
    protected $model;
    protected QueryBuilder $queryBuilder;

    
    /**
     * Create a new Repository instance.
     *
     * @param QueryBuilder $queryBuilder The query builder instance.
     */
    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
        $this->setModel();
    }

    /**
     * Get the model associated with the repository.
     *
     * @return Model The model instance.
     */
    abstract public function model();

    /**
     * Set the model instance for the repository.
     *
     * @return void
     */
    public function setModel()
    {
        $this->model = $this->model();
    }

    /**
     * Get the table name associated with the model.
     *
     * @return string The table name.
     */
    protected function table()
    {
        return $this->model::$table;
    }

    /* QUERIES */

    /**
     * Get a record by its ID.
     *
     * @param int $id The ID of the record.
     * @return mixed The query result.
     */
    public function getById($id)
    {
        $filter = "id = :id";
        $result = $this->queryBuilder->table($this->table())->select($filter, [':id' => $id]);
        if ($result) {
            return new $this->model($result[0]);
        }
        return null;
    }

    /**
     * Get all records.
     *
     * @return array The query results as instances of the model.
     */
    public function getAll()
    {
        $results = $this->queryBuilder->table($this->table())->select();
        $models = [];
        foreach ($results as $result) {
            $models[] = new $this->model($result);
        }
        return $models;
    }

    /**
     * Create a new record.
     *
     * @param array $data The data to be inserted.
     * @return mixed The query result.
     */
    public function create(array $data)
    {
        $model = new $this->model($data);
        if ($model) {
            $id = $this->queryBuilder->table($this->table())->insert($model->toArray());
        }
        if ($id) {
            $model = $this->getById($id);
            return $model;
        }
    }

    /**
     * Update a record by ID.
     *
     * @param int $id The ID of the record to be updated.
     * @param array $data The data to be updated.
     * @return mixed The updated model.
     */
    public function update(int $id, array $data)
    {
        // Validar parámetros utilizando el modelo
        $class = $this->model;
        $model = new $class($data);
        if ($model) {
            $filter = "id = :id";
            $params = [':id' => $id];
            $success = $this->queryBuilder->table($this->table())->update($model->toArray(), $filter, $params);
        }
        if ($success) {
            $data = $this->getById($id);
            $model = new $this->model($data);
            return $model;
        }
        return null;
    }

    /**
     * Delete a record by ID.
     *
     * @param int $id The ID of the record to be deleted.
     * @return bool True on success, false on failure.
     */
    public function delete(int $id)
    {
        $filter = "id = :id";
        $params = [':id' => $id];
        return $this->queryBuilder->table($this->table())->delete($filter, $params);
    }
}
