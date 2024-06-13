<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class InternalHardDrive extends Component {

    static public string $tableHijo = '"internalHardDrive"';

    protected array $fieldsHijo = [
        "capacity" => null,
        "type" => null,
        "form_factor" => null,
        "interface" => null,
    ];

    public function setCapacity(?int $capacity){
        $this->fieldsHijo["capacity"] = $capacity;
    }
    public function getCapacity(): ?int{
        return $this->fieldsHijo["capacity"];
    }

    public function setType(?string $type){
        $this->fieldsHijo["type"] = $type;
    }
    public function getType(): ?string{
        return $this->fieldsHijo["type"];
    }

    public function setForm_factor(?string $formFactor){
        $this->fieldsHijo["form_factor"] = $formFactor;
    }
    public function getForm_factor(): ?string{
        return $this->fieldsHijo["form_factor"];
    }

    public function setInterface(?string $interface){
        $this->fieldsHijo["interface"] = $interface;
    }
    public function getInterface(): ?string{
        return $this->fieldsHijo["interface"];
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