<?php

namespace Paw\App\Controllers;
use Twig\Environment;

class Controller
{  
    private $twig;
    public string $imagesDir = __DIR__ . "/../../../public/images/";
    public array $nav = [
        [
            "href" => "/",
            "name" => "Inicio",
            "role" => ["user","guest"]
        ],
        [
            "href" => "/assemble_pc",
            "name" => "Arma tu PC",
            "role" => ["user","guest"]
        ],
        [
            "href" => "/products?page=0",
            "name" => "Productos",
            "role" => ["user","guest"]
        ],
        [
            "href" => "/branches",
            "name" => "Branches",
            "role" => ["user","guest"]
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

    public array $navAccount = [
        [
            "href" => "/login",
            "name" => "Log In",
            "role" => "guest"
        ],
        [
            "href" => "/signin",
            "name" => "Sign In",
            "role" => "guest"
        ],
        [
            "href" => "/account",
            "name" => "Account",
            "role" => ["user","admin"]
        ],
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

    
    public function __construct(Environment $twig) {
        $this->twig = $twig;
    }
    
    public function render($url, $title, $request, $values = null) {
        //Modo de uso: ingresar la ubicacion del archivo, el titulo, y un array key value con las demas variables (opcional)
        $varsRender = [
            "footer" => $this->footer,
            "title" => $title,
            "pathInfo" => $request->url(),
            "userLogged" => $this->userIsLogged(),
            "nav" => $this->nav,
            "navAccount" => $this->navAccount,
            "userRole" => $this->getUserRole(),
        ];

        if($values) {
            $varsRender = array_merge($varsRender, $values);
        }
        echo $this->twig->render($url, $varsRender);
    }

    public function redirect($path) {
        header("Location: ". getenv('APP_URL') . $path);
        exit();
    }

    public function userIsLogged() {
        return (isset($_SESSION["user_id"]));
    }

    public function getUserRole() {
        return $_SESSION['user_role'] ?? "guest";
    }

    public function verifyIsLogged($originPath) {
        if (!$this->userIsLogged()) {
            $_SESSION["loopback"] = $originPath;
            $this->redirect("/login");
        }
    }

    public function sanitizeInput($input) {
        return htmlspecialchars(trim($input));
    }
}