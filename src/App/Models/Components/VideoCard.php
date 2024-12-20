<?php

namespace Paw\App\Models\Components;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class VideoCard extends SpecificComponent {

    static public string $tableChild = 'videoCard';

    public array $fields = [
        "chipset" => null,
        "memory" => null,
        "core_clock" =>	null,
        "boost_clock" => null,
    ];

    public function compatibility(SpecificComponent $component): bool{
        $allTypes = ["CasePc", "Cpu", "CpuFan", "InternalHardDrive", "Memory", "Monitor", "Motherboard", "PowerSupply", "VideoCard"];
        $types = ["CasePc", "Cpu", "CpuFan", "InternalHardDrive", "Memory", "Monitor", "Motherboard", "PowerSupply", "VideoCard"];

        $namespacePrefix = "Paw\\App\\Models\\Components\\";
        foreach ($types as &$type) {
            $type = $namespacePrefix . $type;
        }
        
        $componentStr = get_class($component);
        if (in_array($componentStr, $types)) {
            return true;
        }

        return false;
    }

    public function setChipset(string $chipset) {
        if ($chipset)
            $this->fields["chipset"] = $chipset;
    }

    public function getChipset(): ?string
    {
        return $this->fields["chipset"];
    }

    public function setMemory(int $memory) {
        if ($memory)
            $this->fields["memory"] = $memory;
    }

    public function getMemory(): ?int
    {
        return $this->fields["memory"];
    }

    public function setCore_clock(int $core_clock) {
        if ($core_clock)
            $this->fields["core_clock"] = $core_clock;
    }

    public function getCore_clock(): ?int
    {
        return $this->fields["core_clock"];
    }

    public function setBoost_clock(int $boost_clock) {
        if ($boost_clock)
            $this->fields["boost_clock"] = $boost_clock;
    }

    public function getBoost_clock(): ?int
    {
        return $this->fields["boost_clock"];
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