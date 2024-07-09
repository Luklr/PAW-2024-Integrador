<?php

namespace Paw\App\Models\Components;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class Motherboard extends SpecificComponent {

    static public string $tableChild = '"motherboard"';

    protected array $fields = [
        "socket" => null,
        "memory_slots" => null,
    ];

    protected function compatibility(SpecificComponent $component){
        $allTypes = ["CasePc", "Cpu", "CpuFan", "InternalHardDrive", "Memory", "Monitor", "Motherboard", "PowerSuply", "VideoCard"];
        $types = ["CasePc", "Cpu", "CpuFan", "InternalHardDrive", "Monitor", "Motherboard", "PowerSuply", "VideoCard"];

        $componentStr = get_class($component);
        if (in_array($componentStr, $types)) {
            return true;
        }

        if ($componentStr == "Memory"){
            $parts = explode(',', $component->getModules());
            $memory_slots = $parts[0];
            if ($component->getMemory_slots() >= $memory_slots){
                return true;
            }
        }

        return false;
    }

    public function setSocket(?string $socket){
        $this->fields["socket"] = $socket;
    }
    public function getSocket(): ?string{
        return $this->fields["socket"];
    }

    public function setMemory_slots(?int $memorySlots){
        $this->fields["memory_slots"] = $memorySlots;
    }
    public function getMemory_slots(): ?int{
        return $this->fields["memory_slots"];
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