<?php

namespace Paw\App\Models\Components;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class Component extends Model {

    static public string $table = "component";

    protected array $fields = [
        "id" => null,
        "description" => null,
        "price" => null,
        "stock" => null,
        "path_img" => null
    ];

    protected array $fieldsChild = [];

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

    public function getKeys(): ?array
    {
        $data = array_merge($this->fields, $this->fieldsChild);
        unset($data["id"]);
        unset($data["path_img"]);
        return array_keys($data);
    }

    public function setStock(int $stock) {
        if ($stock >= 0) {
            $this->fields["stock"] = $stock;
        }
        else  throw new InvalidValueFormatException("The stock cant be lower than 0");
    }

    public function getStock(): ?int
    {
        return $this->fields["stock"];
    }

    public function setPath_img(string $path_img) {
        $this->fields["path_img"] = $path_img;
    }

    public function getPath_img(): ?string
    {
        return $this->fields["path_img"];
    }
}