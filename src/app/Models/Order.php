<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class Order extends Model {
    protected array $fields = [
        "id" => null,
        "orderDate" => null,
        "deliveryDate" => null,
        "orderPrice" => null,
        "deliveryPrice" => null,
        "user" => null,
        "branch" => null,
        "status" => null,
        "components" => []
    ];

    public function __construct(array $values) {
        $this->set($values);
    }
    
    public function setId(int $id) {
        $this->fields["id"] = $id;
    }

    public function getId(): ?int
    {
        return $this->fields["id"];
    }

    public function setOrderDate(DateTime $orderDate) {
        $this->fields["orderDate"] = $orderDate;
    }

    public function getOrderDate(): ?DateTime
    {
        return $this->fields["orderDate"];
    }

    public function setDeliveryDate(DateTime $deliveryDate) {
        $this->fields["deliveryDate"] = $deliveryDate;
    }

    public function getDeliveryDate(): ?DateTime
    {
        return $this->fields["deliveryDate"];
    }

    public function setOrderPrice(float $price) {
        if ($price < 0.0){
            throw new InvalidValueFormatException("The order price must not be minor than 0.0$");
        }
        $this->fields["orderPrice"] = $price;
    }

    public function getOrderPrice(): ?float
    {
        return $this->fields["orderPrice"];
    }

    public function setDeliveryPrice(float $price) {
        if ($price < 0.0){
            throw new InvalidValueFormatException("The delivery price must not be minor than 0.0$");
        }
        $this->fields["deliveryPrice"] = $price;
    }

    public function getDeliveryPrice(): ?float
    {
        return $this->fields["deliveryPrice"];
    }

    public function setUser(User $user) {
        $this->fields["user"] = $user;
    }

    public function getUser(): ?User
    {
        return $this->fields["user"];
    }

    public function setBranch(Branch $branch) {
        $this->fields["branch"] = $branch;
    }

    public function getBranch(): ?Branch
    {
        return $this->fields["branch"];
    }

    public function setStatus(Status $status) {
        $this->fields["status"] = $status;
    }

    public function getStatus(): ?string
    {
        return ($this->fields["status"])->label();
    }

    public function setComponents(array $components) {
        $this->fields["components"] = $components;
    }

    public function getComponents(): ?array
    {
        return $this->fields["components"];
    }

    public function pay() {
        (this->fields["status"])::PREPARING;
    }

    public function dispatch(){
        (this->fields["status"])::DISPATCHED;
    }

    public function readyForPickup(){
        (this->fields["status"])::READY_FOR_PICKUP;
    }

    public function delivered(){
        (this->fields["status"])::DELIVERED;
    }

    public function toArray(): array
    {
        $data = [];
        foreach (array_keys($this->fields) as $field) {
            $method = "get" . ucfirst($field);
            $data[$field] = $this->$method();
        }
        return $data;
    }
}