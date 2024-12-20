<?php

namespace Paw\App\Models\Components;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class PowerSupply extends SpecificComponent {

    static public string $tableChild = 'powerSupply';

    protected array $fields = [
        "type" => null,
        "efficiency" => null,    
        "wattage" => null,    
        "modular" => null
    ];

    public function compatibility(SpecificComponent $component): bool{
        $allTypes = ["CasePc", "Cpu", "CpuFan", "InternalHardDrive", "Memory", "Monitor", "Motherboard", "PowerSupply", "VideoCard"];
        $types = ["CasePc", "Cpu", "CpuFan", "InternalHardDrive", "Memory", "Monitor", "Motherboard", "PowerSupply", "VideoCard"];

        $namespacePrefix = "Paw\\App\\Models\\Components\\";
        foreach ($types as &$type) {
            $type = $namespacePrefix . $type;
        }

        $componentStr = get_class($component);
        if (in_array($componentStr, $types)) {
            return true;
        }

        if ($componentStr == "Paw\App\Models\Components\CasePc"){
            if ($this->getType() == $component->getType()){
                return true;
            }
        }

        return false;
    }

    public function setType(?string $type){
        $this->fields["type"] = $type;
    }
    public function getType(): ?string{
        return $this->fields["type"];
    }

    public function setEfficiency(?string $efficiency){
        $this->fields["efficiency"] = $efficiency;
    }
    public function getEfficiency(): ?string{
        return $this->fields["efficiency"];
    }

    public function setWattage(?int $wattage){
        $this->fields["wattage"] = $wattage;
    }
    public function getWattage(): ?int{
        return $this->fields["wattage"];
    }

    public function setModular(?bool $modular){
        $this->fields["modular"] = $modular;
    }
    public function getModular(): ?bool{
        return $this->fields["modular"];
    }

    public function toArrayChild(): array
    {
        $data = [ "component_id" => $this->getId()];
        foreach (array_keys($this->fields) as $field) {
            $method = "get" . ucfirst($field);
            $data[$field] = $this->$method();
        }
        return $data;
    }
}