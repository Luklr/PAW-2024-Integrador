<?php

namespace Paw\App\Models\Components;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class Memory extends SpecificComponent {

    static public string $tableChild = '"memory"';

    protected array $fields = [
        "speed" => null,
        "modules" => null,
    ];

    protected function compatibility(SpecificComponent $component){
        $allTypes = ["CasePc", "Cpu", "CpuFan", "InternalHardDrive", "Memory", "Monitor", "Motherboard", "PowerSuply", "VideoCard"];
        $types = ["CasePc", "Cpu", "CpuFan", "InternalHardDrive", "Memory", "Monitor", "PowerSuply", "VideoCard"];

        $componentStr = get_class($component);
        if (in_array($componentStr, $types)) {
            return true;
        }

        if ($componentStr == "Motherboard"){
            $parts = explode(',', $this->getModules());
            $memory_slots = $parts[0];
            if ($component->getMemory_slots() >= $memory_slots){
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

    public function setModules(?string $modules){
        $this->fields["modules"] = $modules;
    }
    public function getModules(): ?string{
        return $this->fields["modules"];
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