<?php

namespace Paw\App\Models\Components;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class InternalHardDrive extends SpecificComponent {

    static public string $tableChild = 'internalHardDrive';

    protected array $fields = [
        "capacity" => null,
        "type" => null,
        "form_factor" => null,
        "interface" => null,
    ];

    protected function compatibility(SpecificComponent $component){
        $allTypes = ["CasePc", "Cpu", "CpuFan", "InternalHardDrive", "Memory", "Monitor", "Motherboard", "PowerSupply", "VideoCard"];
        $types = ["CasePc", "Cpu", "CpuFan", "InternalHardDrive", "Memory", "Monitor", "Motherboard", "PowerSupply", "VideoCard"];

        $componentStr = get_class($component);
        if (in_array($componentStr, $types)) {
            return true;
        }

        return false;
    }

    public function setCapacity(?int $capacity){
        $this->fields["capacity"] = $capacity;
    }
    public function getCapacity(): ?int{
        return $this->fields["capacity"];
    }

    public function setType(?string $type){
        $this->fields["type"] = $type;
    }
    public function getType(): ?string{
        return $this->fields["type"];
    }

    public function setForm_factor(?string $formFactor){
        $this->fields["form_factor"] = $formFactor;
    }
    public function getForm_factor(): ?string{
        return $this->fields["form_factor"];
    }

    public function setInterface(?string $interface){
        $this->fields["interface"] = $interface;
    }
    public function getInterface(): ?string{
        return $this->fields["interface"];
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