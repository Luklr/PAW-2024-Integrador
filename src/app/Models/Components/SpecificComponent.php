<?php

namespace Paw\App\Models\Components;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

abstract class SpecificComponent extends Model {
    /*
     * Return true or false
     */
    protected abstract function compatibility(SpecificComponent $component2);

    public function compatibleWith(SpecificComponent $component2){
        return $component2->compatibility($this);
    }

}