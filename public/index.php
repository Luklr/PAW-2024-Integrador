<?php

require __DIR__ . "/../src/bootstrap.php";

session_start();
$router->direct($request);