<?php

function view($view, $data = []) {
    $viewFile = __DIR__ . "/App/views/{$view}.view.php";

    if (!file_exists($viewFile)) {
        throw new Exception("Vista no encontrada: {$view}");
    }

    extract($data);
    require $viewFile;
}

/*
class Request {
    private $data;

    public function __construct() {
        $this->data = $_SERVER;
    }

    public function get($key) {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public function httpMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function url() {
        return parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    }

    public function input($key): String {
        if ($this->httpMethod()=='GET') {
            return htmlentities($_GET[$key]);
        } else {
            return htmlentities($_POST[$key]);
        }
    }
}

$request = new Request();

function request() {
    global $request;
    return $request;
}

*/