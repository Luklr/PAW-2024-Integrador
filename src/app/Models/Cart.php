<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\App\Models\Components\Component;
use Paw\Core\Exceptions\InvalidValueFormatException;

class Cart extends Model {
    protected array $fields = [
        "user" => null,
        "components" => []
    ];

    public function setUser(User $user) {
        $this->fields["user"] = $user;
    }
    public function getUser(): ?User
    {
        return $this->fields["user"];
    }

    public function setComponents(array $components) {
        $this->fields["components"] = $components;
    }

    public function getComponents(): ?array
    {
        return $this->fields["components"];
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

    public function addComponent(Component $component, int $quantity) {
        $componentId = $component->getId();
        
        if (isset($this->fields["components"][$componentId])) {
            if ($component->getStock() < ($quantity + $this->fields["components"][$componentId]["quantity"])) 
                throw new InvalidValueFormatException("The quantity can't be below than the stock ({$component->getStock()})");
            $this->fields["components"][$componentId]["quantity"] += $quantity;
        } else {
            if ($component->getStock() < $quantity) 
                throw new InvalidValueFormatException("The quantity can't be below than the stock ({$component->getStock()})");
            $this->fields["components"][$componentId] = [
                "component" => $component,
                "quantity" => $quantity
            ];
        }
    }

    public function deleteComponent(Component $component){
        $componentId = $component->getId();
        unset($this->fields['components'][$componentId]);
    }

    public function editComponentQuantity(Component $component, int $quantity){
        $componentId = $component->getId();
        if ($component->getStock() < $quantity) 
            throw new InvalidValueFormatException("The quantity can't be below than the stock ({$component->getStock()})");
        
        $this->fields["components"][$componentId]["quantity"] = $quantity;
    }
}