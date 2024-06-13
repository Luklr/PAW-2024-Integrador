<?php

namespace Paw\App\Models\Components;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class CasePc extends Component {

    static public string $tableChild = '"casePc"';

    protected array $fieldsChild = [
        "type" => null,
        "side_panel" => null,
        "external_volume" => null
    ];

    public function setType(string $type){
        $this->fieldsChild["type"] = $type;
    }
    public function getType(): string{
        return $this->fieldsChild["type"];
    }

    public function setSide_panel(string $sidePanel){
        $this->fieldsChild["side_panel"] = $sidePanel;
    }
    public function getSide_panel(): string{
        return $this->fieldsChild["side_panel"];
    }

    public function setExternal_volume(?float $externalVolume){
        $this->fieldsChild["external_volume"] = $externalVolume;
    }
    public function getExternal_volume(): ?float{
        return $this->fieldsChild["external_volume"];
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