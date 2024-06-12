<?php

namespace Paw\App\Interfaces;

use Paw\Core\Exceptions\InvalidValueFormatException;

interface UserInterface {
    public function getName();
    public function getUsername();
    public function getLastname();
    public function getRole();
    public function getEmail();
}