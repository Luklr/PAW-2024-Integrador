<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\App\Models\User;
use Paw\App\Models\Order;
use Paw\Core\Exceptions\InvalidValueFormatException;

class Notification extends Model {
    static public string $table = '"notification"';

    protected array $fields = [
        "id" => null,
        "notification_type" => [
            "id" => null,
            "name" => null,
            "description" => null
        ],
        "seen" => null,
        "order" => null,
        "timestamp" => null,
        "user" => null,
    ];
    
    public function getId(): ?int {
        return $this->fields['id'];
    }
    public function setId(int $id): void {
        $this->fields['id'] = $id;
    }

    public function getNotification_type(): ?array {
        return $this->fields['notification_type'];
    }
    public function setNotification_type(array $type): void {
        $this->fields['notification_type'] = $type;
    }

    public function getSeen(): ?bool {
        return $this->fields['seen'];
    }
    public function setSeen(bool $seen): void {
        $this->fields['seen'] = $seen;
    }
    public function markAsSeen(): void {
        $this->fields["seen"] = true;
    }

    public function getOrder(): ?Order {
        return $this->fields['order'];
    }
    public function setOrder(Order $order): void {
        $this->fields['order'] = $order;
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