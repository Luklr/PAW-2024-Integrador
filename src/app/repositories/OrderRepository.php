<?php

namespace Paw\App\Repositories;

use Paw\Core\Database\QueryBuilder;
use Paw\App\Models\Components\Component;
use Paw\App\Models\Status;
use Paw\App\Models\Order;
use Paw\App\Models\User;
use Paw\App\Models\Branch;
use Paw\App\Models\Address;
use Paw\App\Repositories\ComponentRepository;
use Paw\App\Repositories\UserRepository;
use Paw\App\Repositories\AddressRepository;
use Paw\App\Repositories\BranchRepository;

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

        # obtengo el usuario
        $userRepository = UserRepository::getInstance();
        $userId = $order[0]["user_id"];
        $user = $userRepository->getById($userId);

        if($order && $componentsArray){
            # paso el status de str a enum
            $order[0]["status"] = Status::fromString($order[0]["status"]);
            
            # paso las fechas a DateTime()
            if ($order[0]["orderdate"]) {
                $order[0]["orderdate"] = new \DateTime($order[0]["orderdate"]);
            }
            if ($order[0]["deliverydate"]) {
                $order[0]["deliverydate"] = new \DateTime($order[0]["deliverydate"]);
            }

            # a ese array lo meto dentro del objeto pedido
            $orderInstance = new Order($order[0]);
            $orderInstance->setComponents($componentsArray);
            $orderInstance->setUser($user);

            # obtengo el branch / address
            if ($order[0]["branch_id"]){
                $branchRepository = BranchRepository::getInstance();
                $branch = $branchRepository->getById($order[0]["branch_id"]);
                $orderInstance->setBranch($branch);
            }
            if ($order[0]["address_id"]){
                $addressRepository = AddressRepository::getInstance();
                $address = $addressRepository->getById($order[0]["address_id"]);
                $orderInstance->setAddress($address);
            }
            return $orderInstance;
        }
        return null;
    }

    public function setBranch($idOrder, $idBranch){
        $filter = "id = :id";
        $data = ["branch_id" => $idBranch, "address_id" => null]; 
        $order = self::$queryBuilder->table($this->table())->update($data, $filter, [':id' => $idOrder]);
    }

    public function setAddress($idOrder, $idAddress){
        $filter = "id = :id";
        $data = ["address_id" => $idAddress, "branch_id" => null];
        $order = self::$queryBuilder->table($this->table())->update($data, $filter, [':id' => $idOrder]);
    }

    public function setStatus($order){
        $filter = "id = :id";
        $data = ["status" => $order->getStatus()];
        $idOrder = $order->getId();
        $order = self::$queryBuilder->table($this->table())->update($data, $filter, [':id' => $idOrder]);
    }

    public function create(array $data)
    {
        $model = new $this->model($data);
        $components = $model->getComponents();
        $modelArray = $model->toArray();
        unset($modelArray["components"]);
        if ($model) {
            if (isset($modelArray["orderdate"]) || $modelArray["orderdate"]){
                $modelArray["orderdate"] = $modelArray["orderdate"]->format('Y-m-d H:i:s');
            }

            if (isset($modelArray["user"])){
                $modelArray["user_id"] = $modelArray["user"]->getId();
                unset($modelArray["user"]);
            }

            if (isset($modelArray["branch"]))
                $modelArray["branch_id"] = $modelArray["branch"];
            else
                $modelArray["branch_id"] = null;
            unset($modelArray["branch"]);

            if (isset($modelArray["address"]))
                $modelArray["address_id"] = $modelArray["address"];
            else
                $modelArray["address_id"] = null;
            unset($modelArray["address"]);

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