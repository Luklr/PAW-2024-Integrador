<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class CasePc extends Component {
    protected array $fields = [
        "id" => null,
        "name" => null,
        "description" => null,
        "price" => null,
        "type" => null,
        "side_panel" => null,
        "external_volume" => null,    
        "internal_35_bays" => null,
    ];

    public function setType(string $type){
        $this->fields["type"] = $type;
    }
    public function getType(): string{
        return $this->fields["type"];
    }

    public function setSide_panel(string $sidePanel){
        $this->fields["side_panel"] = $sidePanel;
    }
    public function getSide_panel(): string{
        return $this->fields["side_panel"];
    }

    public function setExternal_volume(?string $externalVolume){
        $this->fields["external_volume"] = $externalVolume;
    }
    public function getExternal_volume(): ?string{
        return $this->fields["external_volume"];
    }

    public function setInternal_35_bays(?int $internal35Bays){
        $this->fields["internal_35_bays"] = $internal35Bays;
    }
    public function getInternal_35_bays(): ?int{
        return $this->fields["internal_35_bays"];
    }
}