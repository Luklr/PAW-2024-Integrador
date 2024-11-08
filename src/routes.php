<?php

use Paw\Core\Router;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . '/App/views');
$twig = new Environment($loader);

// Instanciar el Router con Twig

#var_dump(file_exists("Paw/core/Router"));

#if ( ! class_exists('Paw/Core/Model')) 
#    die('There is no hope!');

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
$router->get("/assemble_pc_power_supply", 'AssemblePcController@assemblePcPowerSupply');
$router->get("/templates", 'AssemblePcController@templates');
$router->get("/template", 'AssemblePcController@template');
$router->get("/products_page", "AssemblePcController@productsPage");
$router->get("/stock_id", "AssemblePcController@stockById");
$router->get("/verify_assemble_pc_next_component", "AssemblePcController@verifyAssemblePcNextComponent");

$router->get("/about_us", "OrgInformationController@aboutUs");
$router->get("/branches", "OrgInformationController@branches");
$router->get("/consumer_defense", "OrgInformationController@consumerDefense");
$router->get("/contacts", "OrgInformationController@contacts");

$router->post("/add_to_cart", "PaymentController@addComponentToCart");
$router->get("/branch_selection", "PaymentController@branchSelection");
$router->get("/cart", "PaymentController@cart");
$router->post("/cart", "PaymentController@cartToOrder");
$router->get("/confirm_order", "PaymentController@confirmOrder");
$router->post("/confirm_order", "PaymentController@confirmOrderPost");
$router->get("/registered_order", "PaymentController@registeredOrder");
$router->get("/enter_address", "PaymentController@enterAddress");
$router->get("/order_pickup", "PaymentController@orderPickUp");
$router->get("/delete_item_cart", "PaymentController@deleteItemCart");
$router->get("/set_branch_order", "PaymentController@setBranchOrder");
$router->get("/set_address_order", "PaymentController@setAddressOrder");


$router->get("/create_product", "IntranetController@createProduct");
$router->post("/create_product", "IntranetController@createProductPost");
$router->get("/management_orders", "IntranetController@managementOrders");
$router->get("/management_order", "IntranetController@managementOrder");
$router->get("/get_orders_management", "IntranetController@getOrdersForManagement");
$router->post("/set_order_status", "IntranetController@setOrderStatus");
$router->post("/set_delivery_price", "IntranetController@setDeliveryPrice");

$router->get("/login", "UserController@login");
$router->post("/login", "UserController@loginPost");
$router->get("/signin", "UserController@signin");
$router->post("/signin", "UserController@signinPost");
$router->get("/account", "UserController@account");
$router->get("/account/logout", "UserController@logout");
$router->get("/account/set_address", "UserController@setAddress");
$router->post("/account/set_address", "UserController@setAddressForm");
// $router->get("/get_notifications", "UserController@getNotifications");
$router->get("/delete_notification", "UserController@deleteNotification");
$router->post("/set_notifications_seen", "UserController@setNotificationsSeen");

$router->get("/forbidden", "ErrorController@forbidden");
$router->get("/not_found", "ErrorController@notFound");
$router->get("/server_error", "ErrorController@internalServerError");