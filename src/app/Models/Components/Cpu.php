<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class Cpu extends SpecificComponent {
    protected array $fields = [
        "id" => null,
        "name" => null,
        "description" => null,
        "price" => null,
    ];

}