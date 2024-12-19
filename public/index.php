<?php

require __DIR__ . "/../src/bootstrap.php";

error_reporting(E_ALL & ~E_DEPRECATED); # Report all errors except E_DEPRECATED

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    exit(0);
}
if (!$request->session()->isLogged()) {
    $request->session()->set("user_role", "guest");
}

$router->direct($request);