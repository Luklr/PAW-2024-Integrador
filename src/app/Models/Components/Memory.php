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