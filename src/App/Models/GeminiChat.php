<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\App\Models\User;
use Paw\Core\Exceptions\InvalidValueFormatException;

class GeminiChat extends Model {
    static public string $table = '"geminiChat"';

    protected array $fields = [
        "id" => null,
        "gemini_msj" => null,
        "timestamp" => null,
        "text" => null,
        "user" => null,
    ];
    
    public function getId(): ?int {
        return $this->fields['id'];
    }
    public function setId(int $id): void {
        $this->fields['id'] = $id;
    }

    public function getGemini_msj(): ?bool {
        return $this->fields['gemini_msj'];
    }
    public function setGemini_msj(bool $gemini_msj): void {
        $this->fields['gemini_msj'] = $gemini_msj;
    }

    public function getText(): ?String {
        return $this->fields['text'];
    }
    public function setText(String $text): void {
        $this->fields['text'] = $text;
    }

    public function getTimestamp(): ?\DateTime {
        return $this->fields['timestamp'];
    }
    public function setTimestamp(\DateTime $timestamp): void {
        $this->fields['timestamp'] = $timestamp;
    }

    public function getUser(): ?User {
        return $this->fields['user'];
    }
    public function setUser(User $user): void {
        $this->fields['user'] = $user;
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