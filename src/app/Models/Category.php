<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class Category extends Model {
    private array $fields = [
        "id" => null,
        "name" => null,
        "description" => null
    ];

    public function __construct(array $values) {
        $this->set($values);
    }

    public function setName(string $name) {
        $nameTrim = trim($name);
        if (strlen($nameTrim) > 60) {
            throw new InvalidValueFormatException("The name must not be major than 60 characters");
        }
        if (strlen($nameTrim) < 1 ) {
            throw new InvalidValueFormatException("The name must not be void");
        }
        $this->fields["name"] = $nameTrim;
    }
    public function getName(): ?string
    {
        return $this->fields["name"];
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

    public function setId(int $id) {
        $this->fields["id"] = $id;
    }

    public function getId(): ?int
    {
        return $this->fields["id"];
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