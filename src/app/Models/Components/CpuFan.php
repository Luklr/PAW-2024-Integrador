<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class CpuFan extends Component {

    static public string $tableHijo = '"cpuFan"';

    protected array $fieldsHijo = [
        "rpm" => null,    
        "noise_level" => null,    
        "size" => null
    ];

    public function setRpm(?int $rpm){
        $this->fieldsHijo["rpm"] = $rpm;
    }
    public function getRpm(): ?int{
        return $this->fieldsHijo["rpm"];
    }

    public function setNoise_level(?int $noiseLevel){
        $this->fieldsHijo["noise_level"] = $noiseLevel;
    }
    public function getNoise_level(): ?int{
        return $this->fieldsHijo["noise_level"];
    }

    public function setSize(?int $size){
        $this->fieldsHijo["size"] = $size;
    }
    public function getSize(): ?int{
        return $this->fieldsHijo["size"];
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