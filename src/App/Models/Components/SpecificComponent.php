<?php

namespace Paw\App\Models\Components;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

abstract class SpecificComponent extends Model {

    protected array $fields = [];

    /*
     * Return true or false
     */
    public abstract function compatibility(SpecificComponent $component): bool;

    

    public function getKeys(): ?array
    {
        $data = $this->fields;
        unset($data["id"]);
        return array_keys($data);
    }
}