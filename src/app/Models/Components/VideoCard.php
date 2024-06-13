<?php

namespace Paw\App\Models\Components;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class VideoCard extends Component {

    static public string $tableChild = '"videoCard"';

    protected array $fieldsChild = [
        "chipset" => null,
        "memory" => null,
        "core_clock" =>	null,
        "boost_clock" => null,
    ];

    public function setChipset(string $chipset) {
        if ($chipset)
            $this->fieldsChild["chipset"] = $chipset;
    }

    public function getChipset(): ?string
    {
        return $this->fieldsChild["chipset"];
    }

    public function setMemory(int $memory) {
        if ($memory)
            $this->fieldsChild["memory"] = $memory;
    }

    public function getMemory(): ?int
    {
        return $this->fieldsChild["memory"];
    }

    public function setCore_clock(int $core_clock) {
        if ($core_clock)
            $this->fieldsChild["core_clock"] = $core_clock;
    }

    public function getCore_clock(): ?int
    {
        return $this->fieldsChild["core_clock"];
    }

    public function setBoost_clock(int $boost_clock) {
        if ($boost_clock)
            $this->fieldsChild["boost_clock"] = $boost_clock;
    }

    public function getBoost_clock(): ?int
    {
        return $this->fieldsChild["boost_clock"];
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