<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class Motherboard extends Component {
    protected array $fields = [
        "id" => null,
        "name" => null,
        "description" => null,
        "price" => null,
        "socket" => null,
        //"max_memory" => null,
        "memory_slots" => null,
    ];

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
}