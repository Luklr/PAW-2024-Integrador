<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class Address extends Model {
    protected array $fields = [
        "id" => null,
        "province" => null,
        "locality" => null,
        "street" => null,
        "domicileNumber" => null,
        "postalCode" => null
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

    public function setDomicileNumber(int $dn) {
        if ($dn < 1) {
            throw new InvalidValueFormatException("The domicile number must not be minor than 1");
        }
        $this->fields["domicileNumber"] = $dn;
    }

    public function getDomicileNumber(): ?int
    {
        return $this->fields["domicileNumber"];
    }

    public function setPostalCode(string $postalCode) {
        $postalCodeTrim = trim($postalCode);
        if (strlen($postalCodeTrim) > 10) {
            throw new InvalidValueFormatException("The postal code must not be major than 10 characters");
        }
        if (strlen($postalCodeTrim) < 1 ) {
            throw new InvalidValueFormatException("The postal code must not be void");
        }
        $this->fields["postalCode"] = $postalCodeTrim;
    }

    public function getPostalCode(): ?string
    {
        return $this->fields["postalCode"];
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