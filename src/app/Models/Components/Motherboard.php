<?php

namespace Paw\App\Models\Components;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class Motherboard extends Component {

    static public string $tableHijo = '"motherboard"';

    protected array $fieldsHijo = [
        "socket" => null,
        "memory_slot" => null,
    ];

    public function setSocket(?string $socket){
        $this->fieldsHijo["socket"] = $socket;
    }
    public function getSocket(): ?string{
        return $this->fieldsHijo["socket"];
    }

    public function setMemory_slot(?int $memorySlots){
        $this->fieldsHijo["memory_slot"] = $memorySlots;
    }
    public function getMemory_slot(): ?int{
        return $this->fieldsHijo["memory_slot"];
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