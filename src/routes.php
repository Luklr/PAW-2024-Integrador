<?php

use Paw\Core\Router;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . '/app/views');
$twig = new Environment($loader);

// Instanciar el Router con Twig
$router = new Router($twig);

$router->get('/','IndexController@index');

$router->get('/products','AssemblePcController@products');
$router->get("/product", "AssemblePcController@product");
$router->get("/assemble_pc", 'AssemblePcController@assemblePc');
$router->get("/assemble_pc_case", 'AssemblePcController@assemblePcCase');
$router->get("/assemble_pc_cpu", 'AssemblePcController@assemblePcCpu');
$router->get("/assemble_pc_gpu", 'AssemblePcController@assemblePcGpu');
$router->get("/assemble_pc_ram", 'AssemblePcController@assemblePcRam');
$router->get("/assemble_pc_motherboard", 'AssemblePcController@assemblePcMotherboard');
$router->get("/assemble_pc_disk", 'AssemblePcController@assemblePcDisk');
$router->get("/assemble_pc_power_suply", 'AssemblePcController@assemblePcPowerSuply');
$router->get("/templates", 'AssemblePcController@templates');
$router->get("/template", 'AssemblePcController@template');
$router->get("/products_page", "AssemblePcController@productsPage");

$router->get("/about_us", "OrgInformationController@aboutUs");
$router->get("/branches", "OrgInformationController@branches");
$router->get("/consumer_defense", "OrgInformationController@consumerDefense");
$router->get("/contacts", "OrgInformationController@contacts");

$router->get("/branch_selection", "PaymentController@branchSelection");
$router->get("/cart", "PaymentController@cart");
$router->get("/confirm_order", "PaymentController@confirmOrder");
$router->get("/enter_address", "PaymentController@enterAddress");
$router->get("/order_pickup", "PaymentController@orderPickUp");

$router->get("/create_product", "IntranetController@createProduct");
$router->post("/create_product", "IntranetController@createProductPost");

$router->get("/login", "UserController@login");
$router->post("/login", "UserController@loginPost");
$router->get("/signin", "UserController@signin");
$router->post("/signin", "UserController@signinPost");
$router->get("/account", "UserController@account");
$router->get("/account/logout", "UserController@logout");

$router->get("/forbidden", "ErrorController@forbidden");
$router->get("/not_found", "ErrorController@notFound");
$router->get("/server_error", "ErrorController@internalServerError");