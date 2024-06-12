<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class VideoCard extends Component {
    protected array $fields = [
        "id" => null,
        "name" => null,
        "description" => null,
        "price" => null,
        "chipset" => null,
        "memory" => null,
        "core_clock" =>	null,
        "boost_clock" => null,
        // "length" => null,
    ];

    public function setChipset(string $chipset) {
        if ($chipset)
            $this->fields["chipset"] = $chipset;
    }

    public function getChipset(): ?string
    {
        return $this->fields["chipset"];
    }

    public function setMemory(string $memory) {
        if ($memory)
            $this->fields["memory"] = $memory;
    }

    public function getMemory(): ?string
    {
        return $this->fields["memory"];
    }

    public function setCore_clock(string $core_clock) {
        if ($core_clock)
            $this->fields["core_clock"] = $core_clock;
    }

    public function getCore_clock(): ?string
    {
        return $this->fields["core_clock"];
    }

    public function setBoost_clock(string $boost_clock) {
        if ($boost_clock)
            $this->fields["boost_clock"] = $boost_clock;
    }

    public function getBoost_clock(): ?string
    {
        return $this->fields["boost_clock"];
    }

}