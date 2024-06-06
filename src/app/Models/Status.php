<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

enum Status: string {
    case PENDING_PAYMENT = "PENDING_PAYMENT";
    case PREPARING = "PREPARING";
    case DISPATCHED = "DISPATCHED";
    case READY_FOR_PICKUP = "READY_FOR_PICKUP";
    case DELIVERED = "DELIVERED";

    public function label(): string {
        return match($this) {
            Status::PENDING_PAYMENT => "PENDING_PAYMENT",
            Status::PREPARING => "PREPARING",
            Status::DISPATCHED => "DISPATCHED",
            Status::READY_FOR_PICKUP => "READY_FOR_PICKUP",
            Status::DELIVERED => "DELIVERED"
        };
    }
}

// // Usar el enumerado con metodos
// $status = Status::InProgress;

// echo $status->label();  // Output: In Progress