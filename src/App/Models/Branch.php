<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class Branch extends Model {
    static public string $table = '"branch"';

    protected array $fields = [
        "id" => null,
        "name" => null,
        "locality" => null,
        "street" => null,
        "number" => null
    ];
    
    public function setId(int $id) {
        $this->fields["id"] = $id;
    }

    public function getId(): ?int
    {
        return $this->fields["id"];
    }

    public function setName(string $name) {
        $nameTrim = trim($name);
        if (strlen($nameTrim) > 60) {
            throw new InvalidValueFormatException("The name must not be major than 60 characters");
        }
        if (strlen($nameTrim) < 1 ) {
            throw new InvalidValueFormatException("The name must not be void");
        }
        $this->fields["name"] = $name;
    }

    public function getName(): ?string
    {
        return $this->fields["name"];
    }

    public function setLocality(string $locality) {
        $localityTrim = trim($locality);
        if (strlen($localityTrim) > 60) {
            throw new InvalidValueFormatException("The locality must not be major than 60 characters");
        }
        if (strlen($localityTrim) < 1 ) {
            throw new InvalidValueFormatException("The locality must not be void");
        }
        $this->fields["locality"] = $localityTrim;
    }

    public function getLocality(): ?string
    {
        return $this->fields["locality"];
    }

    public function setStreet(string $street) {
        $streetTrim = trim($street);
        if (strlen($streetTrim) > 60) {
            throw new InvalidValueFormatException("The street must not be major than 60 characters");
        }
        if (strlen($streetTrim) < 1 ) {
            throw new InvalidValueFormatException("The street must not be void");
        }
        $this->fields["street"] = $streetTrim;
    }

    public function getStreet(): ?string
    {
        return $this->fields["street"];
    }

    public function setNumber(int $n) {
        if ($n < 1) {
            throw new InvalidValueFormatException("The domicile number must not be minor than 1");
        }
        $this->fields["number"] = $n;
    }

    public function getNumber(): ?int
    {
        return $this->fields["number"];
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