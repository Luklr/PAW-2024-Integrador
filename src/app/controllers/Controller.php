<?php

namespace Paw\App\Controllers;

class Controller
{  
    public string $imagesDir = __DIR__ . "/../../../public/images/";
    public array $nav = [
        [
            "href" => "/",
            "name" => "Home",
            "role" => "user"
        ],
        [
            "href" => "/assemble_pc",
            "name" => "Assemble your PC",
            "role" => "user"
        ],
        [
            "href" => "/products?page=0",
            "name" => "Menu",
            "role" => "user"
        ],
        [
            "href" => "/branches",
            "name" => "Branches",
            "role" => "user"
        ],
        [
            "href" => "/news",
            "name" => "News",
            "role" => "user"
        ],
        [
            "href" => "/account",
            "name" => "Account",
            "role" => "user"
        ],
        [
            "href" => "/create_product",
            "name" => "Create product",
            "role" => "admin"
        ],
        [
            "href" => "/management_orders",
            "name" => "Management Orders",
            "role" => "admin"
        ]
    ];

    public array $footer = [
        [
            "href" => "/contacts",
            "name" => "Contacts",
        ],
        [
            "href" => "/about_us",
            "name" => "About us",
        ],
        [
            "href" => "/consumer_defense",
            "name" => "Consumer defense",
        ],
    ];

    
    public function __construct(){}
}