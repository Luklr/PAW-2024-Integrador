<?php

namespace Paw\App\Controllers;
use Paw\Core\Request;
use Twig\Environment;
use Paw\App\Models\Notification;
use Paw\App\Repositories\NotificationRepository;

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
            "href" => "/products",
            "name" => "Productos",
            "role" => ["user","guest"]
        ],
        [
            "href" => "/cart",
            "name" => "Carrito",
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
        [
            "href" => "/branches",
            "name" => "Branches",
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
            "userLogged" => $request->session()->isLogged(),
            "nav" => $this->nav,
            "navAccount" => $this->navAccount,
            "userRole" => $request->session()->get("user_role"),
        ];
        $user = $request->user();
        if ($user) {
            # agregamos username
            $varsRender = array_merge($varsRender,  ["username" => $user->getUsername()]);

            # agregamos las notificaciones
            $notifications = [];
            $notificationRepository = NotificationRepository::getInstance();
            $notificationsObj = $notificationRepository->getByUser($user->getId());
            if ($notificationsObj){
                foreach($notificationsObj as $notificationObj){
                    $notification = $notificationObj->toArray();
                    unset($notification["user"]);
                    $notification["timestamp"] = $notification["timestamp"]->format('Y-m-d H:i:s');
                    $notification["order_id"] = $notification["order"]->getId();
                    unset($notification["order"]);
                    
                    $notifications[] = $notification;
                }
                $varsRender = array_merge($varsRender, ["notifications" => $notifications]);
            }
        }

        if ($values) {
            $varsRender = array_merge($varsRender, $values);
        }
        echo $this->twig->render($url, $varsRender);
    }

    public function redirect($path) {
        header("Location: ". getenv('APP_URL') . $path);
        exit();
    }

    public function sanitizeInput($input) {
        return htmlspecialchars(trim($input));
    }

    public function redirectIfNotLogged(Request $request, $originPath) {
        if (!$request->session()->isLogged()) {
            if ($originPath) {
                $request->session()->set("loopback", $originPath);
            } 
            $this->redirect("/login");
        }
    }

    public function access(Request $request, $originPath, $role) {
        $this->redirectIfNotLogged($request, $originPath);
        if ($request->session()->get("user_role") != $role) {
            $this->redirect("/forbidden");
        }
    }
}