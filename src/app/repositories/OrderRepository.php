<?php

namespace Paw\App\Repositories;

use Paw\Core\Database\QueryBuilder;
use Paw\App\Models\Components\Component;
use Paw\App\Models\Order;
use Paw\App\Models\User;
use Paw\App\Models\Branch;
use Paw\App\Repositories\ComponentRepository;

class OrderRepository extends Repository
{
    protected static $instance = null;

    //protected static $model = User::class;
    public function model() {
        return Order::class; 
    }

    public function getById($id)
    {
        # obtengo el pedido
        $filter = "id = :id";
        $order = self::$queryBuilder->table($this->table())->select($filter, [':id' => $id]);

        # obtengo id producto de los pedido-productos que coincidan en id pedido
        $filter = "order_id = :order_id";
        $order_components = self::$queryBuilder->table("order_component")->select($filter, [':order_id' => $id]);

        # a ese array de productos los obtengo y los convierto en objetos
        $componentRepository = ComponentRepository::getInstance();
        # array de component + quantity
        $componentsArray = [];
        foreach ($order_components as $component) {
            $componentsArray[] = 
            [
                "component" => $componentRepository->getByIdAndType($component["component_id"]),
                "quantity" => $component["quantity"]
            ];
        }

        if($order && $componentsArray){
            # a ese array lo meto dentro del objeto pedido
            $orderInstance = new Order($order[0]);
            $orderInstance->setComponents($componentsArray);
            return $orderInstance;
        }
        return null;
    }

    public function create(array $data)
    {
        $model = new $this->model($data);
        $components = $model->getComponents();
        $modelArray = $model->toArray();
        unset($modelArray["components"]);
        if ($model) {
            $id = self::$queryBuilder->table($this->table())->insert($modelArray);
        }
        if ($id) {
            $componentsArray = [];
            foreach($components as $component){
                $componentsArray[] = self::$queryBuilder->table("order_component")->insert([
                    "order_id" => $id,
                    "component_id" => $component["component"]->getId(),
                    "quantity" => $component["quantity"]
                ]);
            }

            $model = $this->getById($id);
            return $model;
        }
        return null;
    }
}