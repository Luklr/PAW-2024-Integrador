<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class CpuFan extends Component {
    protected array $fields = [
        "id" => null,
        "name" => null,
        "description" => null,
        "price" => null,
        "rpm" => null,    
        "noise_level" => null,    
        "size" => null
    ];

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
}