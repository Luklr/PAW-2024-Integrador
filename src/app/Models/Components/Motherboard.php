<?php

namespace Paw\App\Models\Components;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class Motherboard extends SpecificComponent {

    static public string $tableChild = '"motherboard"';

    protected array $fields = [
        "socket" => null,
        "memory_slot" => null,
    ];

    public function setSocket(?string $socket){
        $this->fields["socket"] = $socket;
    }
    public function getSocket(): ?string{
        return $this->fields["socket"];
    }

    public function setMemory_slot(?int $memorySlots){
        $this->fields["memory_slot"] = $memorySlots;
    }
    public function getMemory_slot(): ?int{
        return $this->fields["memory_slot"];
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