<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class User extends Model{

    static public string $table = '"user"';

    private array $order = [];

    protected array $fields = [
        "id" => null,
        "name" => null,
        "lastname" => null,
        "username" => null,
        "email" => null,
        "password" => null,
        "role" => null,
        "address_id" => null,
    ];

    public function setName(string $name) {
        $nameTrim = trim($name);
        if (strlen($nameTrim) > 40) {
            throw new InvalidValueFormatException("El nombre del usuario no puede ser mayor a 40 caracteres");
        }
        if (strlen($nameTrim) < 1 ) {
            throw new InvalidValueFormatException("El nombre del usuario no puede estar vacío");
        }
        $this->fields["name"] = $nameTrim;
    }

    public function getName(): ?string
    {
        return $this->fields["name"];
    }
    
    public function setLastname(string $lastname) {
        $lastnameTrim = trim($lastname);
        if (strlen($lastnameTrim) > 40) {
            throw new InvalidValueFormatException("El apellido del usuario no puede ser mayor a 40 caracteres");
        }
        if (strlen($lastnameTrim) < 1 ) {
            throw new InvalidValueFormatException("El apellido del usuario no puede estar vacío");
        }
        $this->fields["lastname"] = $lastnameTrim;
    }

    public function getLastname(): ?string
    {
        return $this->fields["lastname"];
    }

    public function setUsername(string $username) {
        $usernameTrim = trim($username);
        if (strlen($usernameTrim) > 20) {
            throw new InvalidValueFormatException("El username no puede ser mayor a 20 caracteres");
        }
        if (strlen($usernameTrim) < 1 ) {
            throw new InvalidValueFormatException("El username no puede estar vacío");
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
            throw new InvalidValueFormatException("El email no es válido");
        }
        if (strlen($emailTrim) > 40) {
            throw new InvalidValueFormatException("El email no puede tener mas de 40 caracteres");
        }
        if (strlen($emailTrim) < 1 ) {
            throw new InvalidValueFormatException("El email no puede estar vacío");
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
        $roleTrim = trim($role);
        if (strlen($roleTrim) > 10) {
            throw new InvalidValueFormatException("El rol no puede ser mayor a 10 caracteres");
        }
        if (strlen($roleTrim) < 1 ) {
            throw new InvalidValueFormatException("El rol no puede estar vacío");
        }
        $this->fields["role"] = $roleTrim;
    }

    public function getRole(): ?string
    {
        return $this->fields["role"];
    }

    public function setAddress_id(Address $address) {
        $this->fields["address_id"] = $address;
    }

    public function getAddress_id(): ?Address
    {
        return $this->fields["address_id"];
    }

    public function setOrder(Order $order) {
        $this->order = $order;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }
}