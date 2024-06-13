<?php

namespace Paw\Core;

class Session
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function delete($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public function destroy()
    {   
        $_SESSION = [];
        setcookie(session_name(), '', time() - 10000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]);
        session_destroy();

    }

    public function all()
    {
        return $_SESSION;
    }

    public function isLogged(): bool
    {
        return !is_null($this->get("user_id"));
    }

}
