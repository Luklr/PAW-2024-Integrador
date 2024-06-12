<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class Template extends Model {
    private array $fields = [
        "id" => null,
        "name" => null,
        "components" => []
    ];

    public function setName(string $name) {
        $nameTrim = trim($name);
        if (strlen($nameTrim) > 60) {
            throw new InvalidValueFormatException("The name must not be major than 60 characters");
        }
        if (strlen($nameTrim) < 1 ) {
            throw new InvalidValueFormatException("The nam must not be void");
        }
        $this->fields["name"] = $nameTrim;
    }

    public function getName(): ?string
    {
        return $this->fields["name"];
    }
    
    public function setId(int $id) {
        $this->fields["id"] = $id;
    }

    public function getId(): ?int
    {
        return $this->fields["id"];
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

    public function addComponent(Component $component){
        array_push($this->fields["components"], $component);
    }

    public function deleteComponent(Component $component){
        foreach($this->fields["components"] as $i => $componentArray){
            if($componentArray->getId() === $component->getId()){
                unset(($this->fields["components"])[$i]);
            }
        }
    }
}