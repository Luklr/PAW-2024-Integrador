<?php

namespace Paw\App\Repositories;

use Paw\Core\Database\QueryBuilder;
use Paw\App\Models\Address;
use Paw\App\Repositories\UserRepository;

class AddressRepository extends Repository
{
    protected static $instance = null;
    //protected static $model = User::class;
    public function model() {
        return Address::class;
    }

    public function getById($id)
    {
        $filter = "id = :id";
        $result = self::$queryBuilder->table($this->table())->select($filter, [':id' => $id]);
        if ($result) {
            $model = new $this->model($result[0]);
            $userRepository = UserRepository::getInstance();
            $user_id = $result[0]["user_id"];
            $user = $userRepository->getById($user_id);
            $model->setUser($user);
            return $model;
        }
        return null;
    }

    public function getByUser($user_id)
    {
        $filter = "user_id = :user_id";
        $results = self::$queryBuilder->table($this->table())->select($filter, [':user_id' => $user_id]);
        if (!$results) {
            return null;
        }
        $models = [];
        $userRepository = UserRepository::getInstance();
        $user = $userRepository->getById($user_id);
        foreach ($results as $result){
            $model = new $this->model($result);
            $model->setUser($user);
            $models[] = $model;
        }
        return $models;
    }

    public function create(array $data)
    {
        $model = new $this->model($data);
        $modelArray = $model->toArray();
        $modelArray["user_id"] = $modelArray["user"]->getId();
        unset($modelArray["user"]);
        if ($model) {
            $id = self::$queryBuilder->table($this->table())->insert($modelArray);
        }
        if ($id){
            $model = $this->getById($id);
            return $model;
        }
        return null;
    }
}