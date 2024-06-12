<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class Memory extends Component {
    protected array $fields = [
        "id" => null,
        "name" => null,
        "description" => null,
        "price" => null,
        "speed" => null,
        "modules" => null,
    ];

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
}