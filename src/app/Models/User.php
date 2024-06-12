<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class User extends Model {
    private array $order = [];

    private array $fields = [
        "id" => null,
        "name" => null,
        "lastname" => null,
        "username" => null,
        "email" => null,
        "password" => null,
        "role" => null,
        "address" => null,
        "cart" => null,
    ];

    public function setName(string $name) {
        $nameTrim = trim($name);
        if (strlen($nameTrim) > 60) {
            throw new InvalidValueFormatException("The name must not be major than 60 characters");
        }
        if (strlen($nameTrim) < 1 ) {
            throw new InvalidValueFormatException("The nam must not be void");
        }
        $this->fields["name"] = $nameTrim;
    }

    public function getName(): ?string
    {
        return $this->fields["name"];
    }
    
    public function setLastname(string $lastname) {
        $lastnameTrim = trim($lastname);
        if (strlen($lastnameTrim) > 60) {
            throw new InvalidValueFormatException("The lastname must not be major than 60 characters");
        }
        if (strlen($lastnameTrim) < 1 ) {
            throw new InvalidValueFormatException("The lastname must not be void");
        }
        $this->fields["lastname"] = $lastnameTrim;
    }

    public function getLastname(): ?string
    {
        return $this->fields["lastname"];
    }

    public function setUsername(string $username) {
        $usernameTrim = trim($username);
        if (strlen($usernameTrim) > 60) {
            throw new InvalidValueFormatException("The username must not be major than 60 characters");
        }
        if (strlen($usernameTrim) < 1 ) {
            throw new InvalidValueFormatException("The username must not be void");
        }
        $this->fields["username"] = $usernameTrim;
    }

    public function getUsername(): ?string
    {
        return $this->fields["username"];
    }

    public function setEmail(string $email) {
        $emailTrim = trim($email);
        if (!filter_var($emailTrim, FILTER_VALIDATE_EMAIL)) {   // Esto se debe hacer en el modelo?
            throw new InvalidValueFormatException("The email is not valid");
        }
        if (strlen($emailTrim) > 40) {
            throw new InvalidValueFormatException("The email must not be major than 40 characters");
        }
        if (strlen($emailTrim) < 1 ) {
            throw new InvalidValueFormatException("The email must not be void");
        }
        $this->fields["email"] = $emailTrim;
    }

    public function getEmail(): ?string
    {
        return $this->fields["email"];
    }

    public function setPassword(string $password) {
        $passwordTrim = trim($password);
        $this->fields["password"] = $passwordTrim;
    }

    public function getPassword(): ?string
    {
        return $this->fields["password"];
    }

    public function setId(int $id) {
        $this->fields["id"] = $id;
    }

    public function getId(): ?int
    {
        return $this->fields["id"];
    }

    public function setRole(string $role) {
        $this->fields["role"] = $role;
    }

    public function getRole(): ?string
    {
        return $this->fields["role"];
    }

    public function setAddress(Address $address) {
        $this->fields["address"] = $address;
    }

    public function getAddress(): ?Address
    {
        return $this->fields["address"];
    }

    public function setOrder(Order $order) {
        $this->fields["order"] = $order;
    }

    public function getOrder(): ?Order
    {
        return $this->fields["order"];
    }

    public function setCart(Cart $cart) {
        $this->fields["cart"] = $cart;
    }

    public function getCart(): ?Cart
    {
        return $this->fields["cart"];
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