<?php

namespace Paw\App\Models\Components;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class InternalHardDrive extends Component {

    static public string $tableChild = '"internalHardDrive"';

    protected array $fieldsChild = [
        "capacity" => null,
        "type" => null,
        "form_factor" => null,
        "interface" => null,
    ];

    public function setCapacity(?int $capacity){
        $this->fieldsChild["capacity"] = $capacity;
    }
    public function getCapacity(): ?int{
        return $this->fieldsChild["capacity"];
    }

    public function setType(?string $type){
        $this->fieldsChild["type"] = $type;
    }
    public function getType(): ?string{
        return $this->fieldsChild["type"];
    }

    public function setForm_factor(?string $formFactor){
        $this->fieldsChild["form_factor"] = $formFactor;
    }
    public function getForm_factor(): ?string{
        return $this->fieldsChild["form_factor"];
    }

    public function setInterface(?string $interface){
        $this->fieldsChild["interface"] = $interface;
    }
    public function getInterface(): ?string{
        return $this->fieldsChild["interface"];
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