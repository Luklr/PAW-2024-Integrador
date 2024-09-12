<?php

namespace Paw\App\Models\Components;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class CpuFan extends SpecificComponent {

    static public string $tableChild = 'cpuFan';

    protected array $fields = [
        "rpm" => null,    
        "noise_level" => null,    
        "size" => null
    ];

    protected function compatibility(SpecificComponent $component){
        $allTypes = ["CasePc", "Cpu", "CpuFan", "InternalHardDrive", "Memory", "Monitor", "Motherboard", "PowerSupply", "VideoCard"];
        $types = ["CasePc", "Cpu", "CpuFan", "InternalHardDrive", "Memory", "Monitor", "Motherboard", "PowerSupply", "VideoCard"];

        $componentStr = get_class($component);
        if (in_array($componentStr, $types)) {
            return true;
        }

        return false;
    }

    public function setRpm(?int $rpm){
        $this->fields["rpm"] = $rpm;
    }
    public function getRpm(): ?int{
        return $this->fields["rpm"];
    }

    public function setNoise_level(?int $noiseLevel){
        $this->fields["noise_level"] = $noiseLevel;
    }
    public function getNoise_level(): ?int{
        return $this->fields["noise_level"];
    }

    public function setSize(?int $size){
        $this->fields["size"] = $size;
    }
    public function getSize(): ?int{
        return $this->fields["size"];
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