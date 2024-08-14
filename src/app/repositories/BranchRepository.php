<?php

namespace Paw\App\Repositories;

use Paw\Core\Database\QueryBuilder;
use Paw\App\Models\Branch;

class BranchRepository extends Repository
{
    protected static $instance = null;
    //protected static $model = User::class;
    public function model() {
        return Branch::class;
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
        if (!$results) {
            return null;
        }
        $models = [];
        foreach ($results as $result){
            $models[] = new $this->model($result);
        }
        return $models;
    }
}