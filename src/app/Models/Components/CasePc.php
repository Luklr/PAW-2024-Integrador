<?php

namespace Paw\App\Models\Components;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class CasePc extends SpecificComponent {

    static public string $tableChild = 'casePc';

    protected array $fields = [
        "type" => null,
        "side_panel" => null,
        "external_volume" => null
    ];

    protected function compatibility(SpecificComponent $component){
        $types = ["CasePc", "Cpu", "CpuFan", "InternalHardDrive", "Memory", "Monitor", "Motherboard", "VideoCard"];
        $componentStr = get_class($component);
        if (in_array($componentStr, $types)) {
            return true;
        }

        if ($componentStr == "PowerSupply"){
            if ($this->getType() == $component->getType()){
                return true;
            }
        }

        return false;
    }

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

    public function setExternal_volume(?float $externalVolume){
        $this->fields["external_volume"] = $externalVolume;
    }
    public function getExternal_volume(): ?float{
        return $this->fields["external_volume"];
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