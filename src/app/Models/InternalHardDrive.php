<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class InternalHardDrive extends Component {
    protected array $fields = [
        "id" => null,
        "name" => null,
        "description" => null,
        "price" => null,
        "capacity" => null,
        "type" => null,
        "form_factor" => null,
        "interface" => null,
    ];

    public function setCapacity(?int $capacity){
        $this->fields["capacity"] = $capacity;
    }
    public function getCapacity(): ?int{
        return $this->fields["capacity"];
    }

    public function setType(?string $type){
        $this->fields["type"] = $type;
    }
    public function getType(): ?string{
        return $this->fields["type"];
    }

    public function setForm_factor(?string $formFactor){
        $this->fields["form_factor"] = $formFactor;
    }
    public function getForm_factor(): ?string{
        return $this->fields["form_factor"];
    }

    public function setInterface(?string $interface){
        $this->fields["interface"] = $interface;
    }
    public function getInterface(): ?string{
        return $this->fields["interface"];
    }
    
    public function toArray(): array
    {
        $data = [];
        foreach (array_keys($this->fields) as $field) {
            $method = "get" . ucfirst($field);
            $data[$field] = $this->$method();
        }
        return $data;
    }
}