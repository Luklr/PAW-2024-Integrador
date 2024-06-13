<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class CpuFan extends Component {

    static public string $tableChild = '"cpuFan"';

    protected array $fieldsChild = [
        "rpm" => null,    
        "noise_level" => null,    
        "size" => null
    ];

    public function setRpm(?int $rpm){
        $this->fieldsChild["rpm"] = $rpm;
    }
    public function getRpm(): ?int{
        return $this->fieldsChild["rpm"];
    }

    public function setNoise_level(?int $noiseLevel){
        $this->fieldsChild["noise_level"] = $noiseLevel;
    }
    public function getNoise_level(): ?int{
        return $this->fieldsChild["noise_level"];
    }

    public function setSize(?int $size){
        $this->fieldsChild["size"] = $size;
    }
    public function getSize(): ?int{
        return $this->fieldsChild["size"];
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