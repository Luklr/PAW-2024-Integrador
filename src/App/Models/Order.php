<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\App\Models\Status;
use Paw\App\Models\User;
use Paw\App\Models\Branch;
use Paw\App\Models\Address;
use Paw\Core\Exceptions\InvalidValueFormatException;
use Paw\Core\Exceptions\InvalidStatusException;

class Order extends Model {

    static public string $table = '"order"';

    protected array $fields = [
        "id" => null,
        "orderdate" => null,
        "deliverydate" => null,
        "orderprice" => null,
        "deliveryprice" => null,
        "user" => null,
        "branch" => null,
        "address" => null,
        "status" => null,
        "components" => []
    ];
    
    public function setId(int $id) {
        $this->fields["id"] = $id;
    }

    public function getId(): ?int
    {
        return $this->fields["id"];
    }

    public function setOrderdate(\DateTime $orderdate) {
        $this->fields["orderdate"] = $orderdate;
    }

    public function getOrderdate(): ?\DateTime
    {
        return $this->fields["orderdate"];
    }

    public function setDeliverydate(\DateTime $deliverydate) {
        $this->fields["deliverydate"] = $deliverydate;
    }

    public function getDeliverydate(): ?\DateTime
    {
        return $this->fields["deliverydate"];
    }

    public function setOrderprice(float $price) {
        if ($price < 0.0){
            throw new InvalidValueFormatException("The order price must not be minor than 0.0$");
        }
        $this->fields["orderprice"] = $price;
    }

    public function getOrderprice(): ?float
    {
        return $this->fields["orderprice"];
    }

    public function setDeliveryprice(float $price) {
        if ($price < 0.0){
            throw new InvalidValueFormatException("The delivery price must not be minor than 0.0$");
        }
        $this->fields["deliveryprice"] = $price;
    }

    public function getDeliveryprice(): ?float
    {
        return $this->fields["deliveryprice"];
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

    public function setAddress(Address $address) {
        $this->fields["address"] = $address;
    }

    public function getAddress(): ?Address
    {
        return $this->fields["address"];
    }

    public function setStatus(Status $status) {
        $this->fields["status"] = $status;
    }

    public function getStatus(): ?string
    {
        return ($this->fields["status"])->label();
    }

    public function setPaymentStatus(string $payment_status) {
        // $payment_status = {status=in_process, status=approved, status=rejected}
        if ($payment_status === '') {
            $this->fields["payment_status"] = $payment_status;
            $this->fields["status"] = Status::PENDING_PAYMENT;
        }
    }

    public function getPaymentStatus(): ?string
    {
        return ($this->fields["payment_status"])->label();
    }

    public function setComponents(array $components) {
        $this->fields["components"] = $components;
    }

    public function getComponents(): ?array
    {
        return $this->fields["components"];
    }

    public function pending() {
        $this->fields["status"]= Status::PENDING_PAYMENT;
        $this->fields["deliverydate"]= null;
    }

    public function pay() {
        $this->fields["status"]= Status::PREPARING;
        $this->fields["deliverydate"]= null;
    }

    public function reject() {
        $this->fields["status"]= Status::REJECTED;
    }

    public function dispatch(){
        if (!$this->fields["address"]) 
            throw new InvalidStatusException("The order must be picked up at the branch");
        if ($this->fields["address"] && !$this->fields["deliveryprice"])
            throw new InvalidStatusException("The order must have the delivery date and price before being dispatched");
        $this->fields["status"]= Status::DISPATCHED;
        $this->setDeliverydate(new \DateTime());
    }

    public function readyForPickup(){
        if (!$this->fields["branch"]) 
            throw new InvalidStatusException("The order must be delivered to the address");
        $this->fields["status"]= Status::READY_FOR_PICKUP;
    }

    public function delivered(){
        if ($this->fields["status"] === Status::PREPARING || $this->fields["status"] === Status::PENDING_PAYMENT) 
            throw new InvalidStatusException("The order must have the status DISPATCHED or PENDING_PAYMENT before being delivered");
        
        $this->fields["status"]= Status::DELIVERED;
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