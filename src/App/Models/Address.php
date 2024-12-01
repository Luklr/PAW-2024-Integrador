<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class Address extends Model {
    static public string $table = '"address"';

    protected array $fields = [
        "id" => null,
        "province" => null,
        "locality" => null,
        "floor" => null,
        "apartment" => null,
        "street" => null,
        "number" => null,
        "postalcode" => null,
        "user" => null
    ];
    
    public function setId(int $id) {
        $this->fields["id"] = $id;
    }

    public function getId(): ?int
    {
        return $this->fields["id"];
    }

    public function setProvince(string $province) {
        $provinceTrim = trim($province);
        if (strlen($provinceTrim) > 60) {
            throw new InvalidValueFormatException("The province must not be major than 60 characters");
        }
        if (strlen($provinceTrim) < 1 ) {
            throw new InvalidValueFormatException("The province must not be void");
        }
        $this->fields["province"] = $provinceTrim;
    }

    public function getProvince(): ?string
    {
        return $this->fields["province"];
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

    public function setFloor(int $f) {
        if (!$f || $f < 1) {
            throw new InvalidValueFormatException("The floor must not be minor than 1");
        }
        $this->fields["floor"] = $f;
    }

    public function getFloor(): ?int
    {
        return $this->fields["floor"];
    }

    public function setApartment(int $a) {
        if (!$a || $a < 1) {
            throw new InvalidValueFormatException("The apartment must not be minor than 1");
        }
        $this->fields["apartment"] = $a;
    }

    public function getApartment(): ?int
    {
        return $this->fields["apartment"];
    }

    public function setPostalcode(string $postalcode) {
        $postalcodeTrim = trim($postalcode);
        if (strlen($postalcodeTrim) > 10) {
            throw new InvalidValueFormatException("The postal code must not be major than 10 characters");
        }
        if (strlen($postalcodeTrim) < 1 ) {
            throw new InvalidValueFormatException("The postal code must not be void");
        }
        $this->fields["postalcode"] = $postalcodeTrim;
    }

    public function getPostalcode(): ?string
    {
        return $this->fields["postalcode"];
    }

    public function setUser(User $user) {
        $this->fields["user"] = $user;
    }
    public function getUser(): ?User
    {
        return $this->fields["user"];
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