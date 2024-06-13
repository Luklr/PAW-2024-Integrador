<?php

namespace Paw\App\Models\Components;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class Monitor extends Component {

    static public string $tableChild = '"monitor"';

    protected array $fieldsChild = [
        "screen_size" => null,
        "resolution" => null,	
        "refresh_rate" => null,
        "response_time" => null,
        "panel_type" => null,
        "aspect_ratio" => null,
    ];
    
    public function setScreen_size(float $screen_size) {
        if ($screen_size > 1)
            $this->fieldsChild["screen_size"] = $screen_size;
    }

    public function getScreen_size(): ?float
    {
        return $this->fieldsChild["screen_size"];
    }

    public function setResolution(string $resolution) {
        if ($resolution)
            $this->fieldsChild["resolution"] = $resolution;
    }

    public function getResolution(): ?string
    {
        return $this->fieldsChild["resolution"];
    }

    public function setRefresh_rate(int $refresh_rate) {
        if ($refresh_rate >= 24)
            $this->fieldsChild["refresh_rate"] = $refresh_rate;
    }

    public function getRefresh_rate(): ?int
    {
        return $this->fieldsChild["refresh_rate"];
    }

    public function setResponse_time(float $response_time) {
        if ($response_time >= 0)
            $this->fieldsChild["response_time"] = $response_time;
    }

    public function getResponse_time(): ?string
    {
        return $this->fieldsChild["response_time"];
    }

    public function setPanel_type(string $panel_type) {
        if ($panel_type)
            $this->fieldsChild["panel_type"] = $panel_type;
    }

    public function getPanel_type(): ?string
    {
        return $this->fieldsChild["panel_type"];
    }

    public function setAspect_ratio(string $aspect_ratio) {
        if ($aspect_ratio)
            $this->fieldsChild["aspect_ratio"] = $aspect_ratio;
    }

    public function getAspect_ratio(): ?string
    {
        return $this->fieldsChild["aspect_ratio"];
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