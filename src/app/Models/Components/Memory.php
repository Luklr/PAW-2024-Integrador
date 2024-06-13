<?php

namespace Paw\App\Models\Components;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class Memory extends Component {

    static public string $tableChild = '"memory"';

    protected array $fieldsChild = [
        "speed" => null,
        "modules" => null,
    ];

    public function setSpeed(?int $speed){
        $this->fieldsChild["speed"] = $speed;
    }
    public function getSpeed(): ?int{
        return $this->fieldsChild["speed"];
    }

    public function setModules(?string $modules){
        $this->fieldsChild["modules"] = $modules;
    }
    public function getModules(): ?string{
        return $this->fieldsChild["modules"];
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