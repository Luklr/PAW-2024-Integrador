<?php

namespace Paw\App\Repositories;

use Paw\Core\Database\QueryBuilder;
use Paw\App\Models\Address;
use Paw\App\Models\Notification;
use Paw\App\Repositories\UserRepository;
use Paw\App\Repositories\OrderRepository;

class NotificationRepository extends Repository
{
    protected static $instance = null;
    //protected static $model = User::class;
    public function model() {
        return Notification::class;
    }

    public function getNotificationTypeById($id)
    {
        $filter = "id = :id";
        $result = self::$queryBuilder->table("notification_type")->select($filter, [':id' => $id]);
        if(!$result){
            return null;
        }
        return $result[0];
    }

    public function getNotificationTypeByName(string $name)
    {
        $filter = "name = :name";
        $result = self::$queryBuilder->table("notification_type")->select($filter, [':name' => $name]);
        if(!$result){
            return null;
        }
        return $result[0];
    }

    public function getById($id)
    {
        $filter = "id = :id";
        $result = self::$queryBuilder->table($this->table())->select($filter, [':id' => $id]);
        if ($result) {
            $result[0]["timestamp"] = new \DateTime($result[0]["timestamp"]);
            $model = new $this->model($result[0]);
            $userRepository = UserRepository::getInstance();
            $orderRepository = OrderRepository::getInstance();
            $user_id = $result[0]["user_id"];
            $order_id = $result[0]["order_id"];
            $notification_type_id = $result[0]["notification_type_id"];
            $notification_type = self::$queryBuilder->table("notification_type")->select($filter, [':id' => $notification_type_id]);
            $user = $userRepository->getById($user_id);
            $order = $orderRepository->getById($user_id);
            $model->setUser($user);
            $model->setOrder($order);
            $model->setNotification_type($notification_type[0]);
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
        foreach ($results as $result){
            $model = $this->getById($result["id"]);
            $models[] = $model;
        }

        usort($models, function($a, $b) {
            return $b->getId() <=> $a->getId();
        });

        return $models;
    }

    public function setSeen(int $id){
        $filter = "id = :id";
        $data = ["seen" => true]; 
        $notification = self::$queryBuilder->table($this->table())->update($data, $filter, [':id' => $id]);
    }

    public function deleteById(int $id){
        $filter = "id = :id";
        $notification = self::$queryBuilder->table($this->table())->delete($filter, [':id' => $id]);
    }

    public function create(array $data)
    {
        $model = new $this->model($data);
        $modelArray = $model->toArray();
        $modelArray["user_id"] = $modelArray["user"]->getId();
        $modelArray["order_id"] = $modelArray["order"]->getId();
        $modelArray["notification_type_id"] = $modelArray["notification_type"]["id"];
        if (isset($modelArray["timestamp"])){
            $modelArray["timestamp"] = $modelArray["timestamp"]->format('Y-m-d H:i:s');
        } else {
            $timeNow = new \DateTime();
            $modelArray["timestamp"] = $timeNow->format('Y-m-d H:i:s');
        }
        
        unset($modelArray["user"]);
        unset($modelArray["order"]);
        unset($modelArray["notification_type"]);
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