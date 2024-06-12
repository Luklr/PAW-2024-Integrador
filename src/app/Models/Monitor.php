<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class Monitor extends Component {
    protected array $fields = [
        "id" => null,
        "name" => null,
        "description" => null,
        "price" => null,
        "screen_size" => null,
        "resolution" => null,	
        "refresh_rate" => null,
        "response_time" => null,
        "panel_type" => null,
        "aspect_ratio" => null,
    ];
    
    public function setScreen_size(float $screen_size) {
        if ($screen_size > 1)
            $this->fields["screen_size"] = $screen_size;
    }

    public function getScreen_size(): ?float
    {
        return $this->fields["screen_size"];
    }

    public function setResolution(string $resolution) {
        if ($resolution)
            $this->fields["resolution"] = $resolution;
    }

    public function getResolution(): ?string
    {
        return $this->fields["resolution"];
    }

    public function setRefresh_rate(int $refresh_rate) {
        if ($refresh_rate >= 24)
            $this->fields["refresh_rate"] = $refresh_rate;
    }

    public function getRefresh_rate(): ?int
    {
        return $this->fields["refresh_rate"];
    }

    public function setResponse_time(float $response_time) {
        if ($response_time >= 0)
            $this->fields["response_time"] = $response_time;
    }

    public function getResponse_time(): ?string
    {
        return $this->fields["response_time"];
    }

    public function setPanel_type(string $panel_type) {
        if ($panel_type)
            $this->fields["panel_type"] = $panel_type;
    }

    public function getPanel_type(): ?string
    {
        return $this->fields["panel_type"];
    }

    public function setAspect_ratio(string $aspect_ratio) {
        if ($aspect_ratio)
            $this->fields["aspect_ratio"] = $aspect_ratio;
    }

    public function getAspect_ratio(): ?string
    {
        return $this->fields["aspect_ratio"];
    }

    public function toArray(): array
    {
        $data = [];
        foreach (array_keys($this->fields) as $field) {
            $method = "get" . ucfirst($field);
            $data[$field] = $this->$method();
        }
        return $data;
    }
}