<?php

namespace Paw\App\Models\Components;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class Cpu extends SpecificComponent {

    static public string $tableChild = 'cpu';

    protected array $fields = [
        "core_count" => null,
        "core_clock" => null,
        "boost_clock" => null,
        'graphics' => null,
        "socket" => null
    ];

    protected function compatibility(SpecificComponent $component){
        $allTypes = ["CasePc", "Cpu", "CpuFan", "InternalHardDrive", "Memory", "Monitor", "Motherboard", "PowerSupply", "VideoCard"];
        $types = ["CasePc", "Cpu", "CpuFan", "InternalHardDrive", "Memory", "Monitor", "PowerSupply", "VideoCard"];

        $componentStr = get_class($component);
        if (in_array($componentStr, $types)) {
            return true;
        }

        if ($componentStr == "Motherboard"){
            $socket = $component->getSocket();
            if ($this->getSocket() === $socket){
                return true;
            }
        }

        return false;
    }

    public function setCore_count(int $core_count) {
        if ($core_clock)
            $this->fields["core_count"] = $core_count;
    }
    public function getCore_count(): ?int
    {
        return $this->fields["core_count"];
    }

    public function setCore_clock(float $core_clock) {
        if ($core_clock)
            $this->fields["core_clock"] = $core_clock;
    }
    public function getCore_clock(): ?float
    {
        return $this->fields["core_clock"];
    }

    public function setBoost_clock(float $boost_clock) {
        if ($boost_clock)
            $this->fields["boost_clock"] = $boost_clock;
    }
    public function getBoost_clock(): ?float
    {
        return $this->fields["boost_clock"];
    }

    public function setGraphics(string $graphics) {
        if ($graphics)
            $this->fields["graphics"] = $graphics;
    }
    public function getGraphics(): ?string
    {
        return $this->fields["graphics"];
    }

    public function setSocket(?string $socket){
        $this->fields["socket"] = $socket;
    }
    public function getSocket(): ?string{
        return $this->fields["socket"];
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