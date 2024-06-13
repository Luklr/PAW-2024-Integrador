<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class VideoCard extends Component {

    static public string $tableHijo = '"videoCard"';

    protected array $fieldsHijo = [
        "chipset" => null,
        "memory" => null,
        "core_clock" =>	null,
        "boost_clock" => null,
    ];

    public function setChipset(string $chipset) {
        if ($chipset)
            $this->fieldsHijo["chipset"] = $chipset;
    }

    public function getChipset(): ?string
    {
        return $this->fieldsHijo["chipset"];
    }

    public function setMemory(int $memory) {
        if ($memory)
            $this->fieldsHijo["memory"] = $memory;
    }

    public function getMemory(): ?int
    {
        return $this->fieldsHijo["memory"];
    }

    public function setCore_clock(int $core_clock) {
        if ($core_clock)
            $this->fieldsHijo["core_clock"] = $core_clock;
    }

    public function getCore_clock(): ?int
    {
        return $this->fieldsHijo["core_clock"];
    }

    public function setBoost_clock(int $boost_clock) {
        if ($boost_clock)
            $this->fieldsHijo["boost_clock"] = $boost_clock;
    }

    public function getBoost_clock(): ?int
    {
        return $this->fieldsHijo["boost_clock"];
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