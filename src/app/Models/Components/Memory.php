<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class Memory extends Component {

    static public string $tableHijo = '"memory"';

    protected array $fieldsHijo = [
        "speed" => null,
        "modules" => null,
    ];

    public function setSpeed(?int $speed){
        $this->fieldsHijo["speed"] = $speed;
    }
    public function getSpeed(): ?int{
        return $this->fieldsHijo["speed"];
    }

    public function setModules(?string $modules){
        $this->fieldsHijo["modules"] = $modules;
    }
    public function getModules(): ?string{
        return $this->fieldsHijo["modules"];
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