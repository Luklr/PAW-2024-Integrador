<?php

namespace Paw\App\Models\Components;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class Component extends Model {

    static public string $table = 'component';

    protected array $fields = [
        "id" => null,
        "description" => null,
        "price" => null,
        "stock" => null,
        "path_img" => null
    ];

    protected SpecificComponent $specificComponent;

    public function __construct(array $values) {
        $this->set($values);
    }

    public function compatibleWith(Component $component2){
        $specificComponent2 = $component2->getSpecificComponent();
        
        if ($specificComponent2){
            return $specificComponent2->compatibility($this->specificComponent);
        }
        
        return false;
    }

    public function setId(int $id) {
        $this->fields["id"] = $id;
    }

    public function getId(): ?int
    {
        return $this->fields["id"];
    }

    public function setPath_img(string $path_img) {
        $this->fields["path_img"] = $path_img;
    }
    public function getPath_img(): ?string
    {
        return $this->fields["path_img"];
    }

    public function setSpecificComponent(SpecificComponent $specificComponent) {
        $this->specificComponent = $specificComponent;
    }

    public function getSpecificComponent(): ?SpecificComponent
    {
        return $this->specificComponent;
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
        $data = $this->fields;
        unset($data["id"]);
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

    public function set(array $values)
    {
        foreach (array_keys($this->fields) as $field) {
            if (!isset($values[$field])) {
                continue;
            }
            $method = "set" . ucfirst($field);
            $this->$method($values[$field]);
        }
        foreach (array_keys($this->fields) as $field) {
            if (!isset($values[$field])) {
                continue;
            }
            $method = "set" . ucfirst($field);
            $this->$method($values[$field]);
        }
    }

    /*
    public function toArray(): array
    {
        $data = [];
        foreach (array_keys($this->fields) as $field) {
            $method = "get" . ucfirst($field);
            $data[$field] = $this->$method();
        }
        foreach (array_keys($this->fields) as $field) {
            $method = "get" . ucfirst($field);
            $data[$field] = $this->$method();
        }
        return $data;
    }
    */
}