<?php

namespace Paw\App\Repositories;

use Paw\Core\Database\QueryBuilder;
use Paw\App\Models\User;
use Paw\App\Models\GeminiChat;

class GeminiChatRepository extends Repository
{
    protected static $instance = null;
    //protected static $model = User::class;
    public function model() {
        return GeminiChat::class;
    }

    public function getById($id){
        $filter = "id = :id";
        $result = self::$queryBuilder->table($this->table())->select($filter, [':id' => $id]);
        
        if (!$result) {
            return null;
        }
        
        $result[0]["timestamp"] = new \DateTime($result[0]["timestamp"]);
        $model = new $this->model($result[0]);
        $userRepository = UserRepository::getInstance();
        $user_id = $result[0]["user_id"];
        $user = $userRepository->getById($user_id);
        $model->setUser($user);
        
        return $model;
    }

    public function getByUser(User $user)
    {
        $user_id = $user->getId();
        $filter = "user_id = :user_id";
        $results = self::$queryBuilder->table($this->table())->select($filter, [':user_id' => $user_id]);
        
        if (!$results) {
            return null;
        }

        $models = [];
        foreach($results as $result){
            $models[] = $this->getById($result["id"]);
            
        }

        
        return $models;
    }

    public function create(array $data)
    {
        $model = new $this->model($data);
        $modelArray = $model->toArray();
        $modelArray["user_id"] = $modelArray["user"]->getId();
        
        if (!isset($modelArray["timestamp"])){
            $timeNow = new \DateTime();
            $modelArray["timestamp"] = $timeNow;
        }
        $modelArray["timestamp"] = $modelArray["timestamp"]->format('Y-m-d H:i:s');
        
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