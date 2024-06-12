<?php

require __DIR__ . "/../src/bootstrap.php";

if (!$request->session()->isLogged()) {
    $request->session()->set("user_role", "guest");
}

$router->direct($request);