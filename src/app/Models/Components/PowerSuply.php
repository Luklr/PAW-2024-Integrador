<?php

namespace Paw\App\Models\Components;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class PowerSuply extends Component {

    static public string $tableChild = '"powerSuply"';

    protected array $fieldsChild = [
        "type" => null,
        "efficiency" => null,    
        "wattage" => null,    
        "modular" => null
    ];

    public function setType(?string $type){
        $this->fieldsChild["type"] = $type;
    }
    public function getType(): ?string{
        return $this->fieldsChild["type"];
    }

    public function setEfficiency(?string $efficiency){
        $this->fieldsChild["efficiency"] = $efficiency;
    }
    public function getEfficiency(): ?string{
        return $this->fieldsChild["efficiency"];
    }

    public function setWattage(?int $wattage){
        $this->fieldsChild["wattage"] = $wattage;
    }
    public function getWattage(): ?int{
        return $this->fieldsChild["wattage"];
    }

    public function setModular(?bool $modular){
        $this->fieldsChild["modular"] = $modular;
    }
    public function getModular(): ?bool{
        return $this->fieldsChild["modular"];
    }

    public function toArrayChild(): array
    {
        $data = [ "component_id" => $this->getId()];
        foreach (array_keys($this->fieldsChild) as $field) {
            $method = "get" . ucfirst($field);
            $data[$field] = $this->$method();
        }
        return $data;
    }
}