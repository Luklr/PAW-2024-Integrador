<?php

namespace Paw\App\Models\Components;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class Memory extends SpecificComponent {

    static public string $tableChild = 'memory';

    protected array $fields = [
        "speed" => null,
        "modules" => null,
        "size" => null
    ];

    public function compatibility(SpecificComponent $component): bool{
        $allTypes = ["CasePc", "Cpu", "CpuFan", "InternalHardDrive", "Memory", "Monitor", "Motherboard", "PowerSupply", "VideoCard"];
        $types = ["CasePc", "Cpu", "CpuFan", "InternalHardDrive", "Memory", "Monitor", "PowerSupply", "VideoCard"];

        $namespacePrefix = "Paw\\App\\Models\\Components\\";
        foreach ($types as &$type) {
            $type = $namespacePrefix . $type;
        }

        $componentStr = get_class($component);
        if (in_array($componentStr, $types)) {
            return true;
        }

        if ($componentStr == "Paw\App\Models\Components\Motherboard"){
            
            if ($component->getMemory_slots() >= $this->getModules()){
                return true;
            }
        }

        return false;
    }

    public function setSpeed(?int $speed){
        $this->fields["speed"] = $speed;
    }
    public function getSpeed(): ?int{
        return $this->fields["speed"];
    }

    public function setModules(?int $modules){
        $this->fields["modules"] = $modules;
    }
    public function getModules(): ?int{
        return $this->fields["modules"];
    }

    public function setSize(?int $size){
        $this->fields["size"] = $size;
    }
    public function getSize(): ?int{
        return $this->fields["size"];
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