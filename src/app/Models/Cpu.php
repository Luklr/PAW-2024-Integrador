<?php

namespace Paw\App\Models\Components;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class Cpu extends Component {
    protected array $fields = [
        "id" => null,
        "name" => null,
        "description" => null,
        "price" => null,
    ];
}