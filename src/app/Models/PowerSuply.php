<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class PowerSuply extends Component {
    protected array $fields = [
        "id" => null,
        "name" => null,
        "description" => null,
        "price" => null,
        "type" => null,
        "efficiency" => null,    
        "wattage" => null,    
        "modular" => null
    ];

    public function setType(?string $type){
        $this->fields["type"] = $type;
    }
    public function getType(): ?string{
        return $this->fields["type"];
    }

    public function setEfficiency(?string $efficiency){
        $this->fields["efficiency"] = $efficiency;
    }
    public function getEfficiency(): ?string{
        return $this->fields["efficiency"];
    }

    public function setWattage(?int $wattage){
        $this->fields["wattage"] = $wattage;
    }
    public function getWattage(): ?int{
        return $this->fields["wattage"];
    }

    public function setModular(?bool $modular){
        $this->fields["modular"] = $modular;
    }
    public function getModular(): ?bool{
        return $this->fields["modular"];
    }
}