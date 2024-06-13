<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class CasePc extends Component {

    static public string $tableHijo = '"casePc"';

    protected array $fieldsHijo = [
        "type" => null,
        "side_panel" => null,
        "external_volume" => null
    ];

    public function setType(string $type){
        $this->fieldsHijo["type"] = $type;
    }
    public function getType(): string{
        return $this->fieldsHijo["type"];
    }

    public function setSide_panel(string $sidePanel){
        $this->fieldsHijo["side_panel"] = $sidePanel;
    }
    public function getSide_panel(): string{
        return $this->fieldsHijo["side_panel"];
    }

    public function setExternal_volume(?float $externalVolume){
        $this->fieldsHijo["external_volume"] = $externalVolume;
    }
    public function getExternal_volume(): ?float{
        return $this->fieldsHijo["external_volume"];
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