<?php

require __DIR__ . "/../src/bootstrap.php";

session_start();
if (!isset($_SESSION["user_id"])) {
    $_SESSION["user_role"] = "guest";
}
$router->direct($request);