<?php

namespace Paw\App\Models\Components;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class Motherboard extends Component {

    static public string $tableChild = '"motherboard"';

    protected array $fieldsChild = [
        "socket" => null,
        "memory_slot" => null,
    ];

    public function setSocket(?string $socket){
        $this->fieldsChild["socket"] = $socket;
    }
    public function getSocket(): ?string{
        return $this->fieldsChild["socket"];
    }

    public function setMemory_slot(?int $memorySlots){
        $this->fieldsChild["memory_slot"] = $memorySlots;
    }
    public function getMemory_slot(): ?int{
        return $this->fieldsChild["memory_slot"];
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