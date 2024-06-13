<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class PowerSuply extends Component {

    static public string $tableHijo = '"powerSuply"';

    protected array $fieldsHijo = [
        "type" => null,
        "efficiency" => null,    
        "wattage" => null,    
        "modular" => null
    ];

    public function setType(?string $type){
        $this->fieldsHijo["type"] = $type;
    }
    public function getType(): ?string{
        return $this->fieldsHijo["type"];
    }

    public function setEfficiency(?string $efficiency){
        $this->fieldsHijo["efficiency"] = $efficiency;
    }
    public function getEfficiency(): ?string{
        return $this->fieldsHijo["efficiency"];
    }

    public function setWattage(?int $wattage){
        $this->fieldsHijo["wattage"] = $wattage;
    }
    public function getWattage(): ?int{
        return $this->fieldsHijo["wattage"];
    }

    public function setModular(?bool $modular){
        $this->fieldsHijo["modular"] = $modular;
    }
    public function getModular(): ?bool{
        return $this->fieldsHijo["modular"];
    }

    public function toArrayHijo(): array
    {
        $data = [ "component_id" => $this->getId()];
        foreach (array_keys($this->fieldsHijo) as $field) {
            $method = "get" . ucfirst($field);
            $data[$field] = $this->$method();
        }
        return $data;
    }
}