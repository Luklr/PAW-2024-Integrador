<?php

namespace Paw\App\Models\Components;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

abstract class SpecificComponent extends Model {

    protected array $fields = [];

    /*
     * Return true or false
     */
    protected abstract function compatibility(SpecificComponent $component2);

    public function compatibleWith(SpecificComponent $component2){
        return $component2->compatibility($this);
    }

    public function getKeys(): ?array
    {
        $data = $this->fields;
        unset($data["id"]);
        return array_keys($data);
    }
}