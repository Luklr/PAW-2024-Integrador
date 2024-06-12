<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

abstract class Component extends Model {
    protected array $fields = [
        "id" => null,
        "description" => null,
        "price" => null
    ];

    public function __construct(array $values) {
        $this->set($values);
    }
    
    public function setId(int $id) {
        $this->fields["id"] = $id;
    }

    public function getId(): ?int
    {
        return $this->fields["id"];
    }

    public function setDescription(string $description) {
        $descriptionTrim = trim($description);
        if (strlen($descriptionTrim) > 512) {
            throw new InvalidValueFormatException("The description must not be major than 512 characters");
        }
        if (strlen($descriptionTrim) < 1 ) {
            throw new InvalidValueFormatException("The description must not be void");
        }
        $this->fields["description"] = $descriptionTrim;
    }

    public function getDescription(): ?string
    {
        return $this->fields["description"];
    }

    public function setPrice(float $price) {
        if ($price < 0.0){
            throw new InvalidValueFormatException("The price must not be minor than 0.0$");
        }
        $this->fields["price"] = $price;
    }

    public function getPrice(): ?float
    {
        return $this->fields["price"];
    }

    public function toArray(): array
    {
        $data = [];
        foreach (array_keys($this->fields) as $field) {
            $method = "get" . ucfirst($field);
            $data[$field] = $this->$method();
        }
        return $data;
    }
}