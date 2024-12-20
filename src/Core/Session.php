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

    public function unset($key)
    {
        unset($_SESSION[$key]);
    }

    public function delete($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public function destroy()
    {   
        setcookie(session_name(), '', time() - 10000);
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
