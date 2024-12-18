<?php

namespace Paw\App\Controllers;
use Paw\Core\Request;
use Paw\App\Repositories\UserRepository;
use Paw\App\Models\Notification;
use Paw\App\Repositories\AddressRepository;
use Paw\App\Repositories\NotificationRepository;
use Twig\Environment;

class UserController extends Controller
{
    private $userRepository;
    private $addressRepository;
    private $notificationRepository;

    public function __construct(Environment $twig) {
        parent::__construct($twig);
        $this->userRepository = UserRepository::getInstance();
        $this->addressRepository = AddressRepository::getInstance();
        $this->notificationRepository = NotificationRepository::getInstance();
    }

    public function logout(Request $request) {
        if ($request->session()->isLogged()) {
            $request->session()->destroy();
        }
        $this->redirect("/");
    }

    public function login(Request $request, $mensaje = "") {
        if ($request->session()->isLogged()) {
            $this->redirect("/");
        }
        echo $this->render('user/login.view.twig', "Log in", $request, ["mensaje" => $mensaje]);
    }

    public function loginPost(Request $request) {
        if ($request->session()->isLogged()) {
            $this->redirect("/");
            return;
        }
        $mensaje = "";
        if (!$request->hasBodyParams(["email", "password"])) {
            $mensaje = "No se encontraron los parámetros necesarios";
            $this->login($request, $mensaje);
            return;
        }
        $values = $request->post();
        $password = $this->sanitizeInput($values["password"]);
        $email = $this->sanitizeInput($values["email"]);

        $hash = hash('sha256', $password);
        $usuario = $this->userRepository->getByEmail($email);

        //var_dump($usuario);die;

        if (!$usuario) {
            $mensaje = "Usuario o contraseña incorrectos";
            $this->login($request, $mensaje);
            return;
        }
        
        $passwordUser = $usuario->getPassword();
        if ($passwordUser !== $hash) {
            $mensaje = "Usuario o contraseña incorrectos";
            $this->login($request, $mensaje);
            return;
        }
        $request->session()->set("user_id", $usuario->getId()); 
        $request->session()->set("user_role", $usuario->getRole());

        // Verifico si tenía que redirigir, sino al home
        $originPath = $request->session()->get("loopback");
        if ($originPath) {
            $request->session()->delete("loopback");
            $this->redirect($originPath);
        } 
        else {
            $this->redirect("/account");
        }
    }

    public function signin(Request $request, $mensaje="") {
        if ($request->session()->isLogged()) {
            $this->redirect("/");
            return;
        }
        echo $this->render('user/signin.view.twig', "Sign in", $request, ["mensaje" => $mensaje]);
    }

    public function signinPost(Request $request) {
        if ($request->session()->isLogged()) {
            $this->redirect("/");
            return;
        }
        $mensaje = "";
        if ($request->hasBodyParams(["nombre", "apellido", "email" ,"username","password","repeatPassword"])) {
            $values = $request->post();
            $pass1 = $this->sanitizeInput($values["password"]);
            $pass2 = $this->sanitizeInput($values["repeatPassword"]);
            if (strlen($pass1) < 8) {
                $mensaje = "La contraseña debe tener 8 caracteres como mínimo";
            } else if ($pass1 !== $pass2) {
                $mensaje = "Las contraseñas no coinciden";
            }
            else {
                try {
                    $hash = hash('sha256', $pass1);
                    $data = array(
                        "name" => $this->sanitizeInput($values["nombre"]),
                        "lastname" => $this->sanitizeInput($values["apellido"]),
                        "username" => $this->sanitizeInput($values["username"]),
                        "email" => $this->sanitizeInput($values["email"]),
                        "role" => "user",
                        "password" => $hash,
                    );
                    $usuario = $this->userRepository->create($data);
                    
                    $request->session()->set("user_id", $usuario->getId()); 
                    $request->session()->set("user_role", $usuario->getRole());

                    $mensaje = "Su usuario fue procesado y registrado con éxito";
                    $this->redirect("/account");
                } catch (InvalidValueFormatException $e) {
                    $mensaje = $e->getMessage();  // Hay que manejar una exception específica nuestra
                } catch (PDOException $e) {
                    if ($e->getCode() == '23505') { // Código de error para violación de restricción única
                        $errorInfo = $e->errorInfo;
                        $detailMessage = $errorInfo[2]; // Detalle del error
                        if (strpos($detailMessage, 'usuario_username') !== false) {
                            $mensaje = "El nombre de usuario ya está en uso";
                        } else {
                            $mensaje = "El correo electrónico ya está en uso";
                        }
                    }
                } catch (Exception $e) {
                    $mensaje = "Hubo un error al procesar su solicitud";
                }
            }
        } else {
            $mensaje = "No se encontraron los parámetros necesarios";
        }
        $this->signin($request, $mensaje);
    }

    public function account(Request $request) {
        $this->redirectIfNotLogged($request, "/");
        echo $this->render('user/account.view.twig', "Account", $request, ["user" => $request->user()]);
    }

    public function setNotificationsSeen(Request $request) {
        if (!$request->session()->isLogged()) {
            http_response_code(404);
            echo json_encode(['message' => 'Forbidden: Access denied']);
        }
        $userId = $request->user()->getId();
        $notifications = $this->notificationRepository->getByUser($userId);
        if ($notifications){
            foreach($notifications as $notification){
                $this->notificationRepository->setSeen($notification->getId());
            }
        }
        
        http_response_code(200);
        echo json_encode(['success' => true]);
    }

    public function deleteNotification(Request $request){
        if (!$request->session()->isLogged()) {
            http_response_code(404);
            echo json_encode(['message' => 'Forbidden: Access denied']);
        }

        if (!$request->get("id")){
            http_response_code(400);
            echo json_encode(['message' => 'Bad Request: Missing parameters']);
        }

        $notificationId = (int) $request->get("id");
        $this->notificationRepository->deleteById($notificationId);
        http_response_code(200);
        echo json_encode(['success' => true]);
    }

    public function setAddress(Request $request) {
        $this->redirectIfNotLogged($request, "/set_address");
        echo $this->render('user/set_address.view.twig', "Set address", $request, ["user" => $request->user()]);
    }

    public function setAddressForm(Request $request) {
        $this->redirectIfNotLogged($request, "/set_address");
        if (!$request->hasBodyParams(["street", "number" ,"postalcode", "province", "locality"])){
            http_response_code(400);
            echo json_encode(['message' => 'Bad Request: Missing parameters']);
        }

        $data = [];
        $data["user"] = $request->user();
        $data["street"] = $request->post("street");
        $data["postalcode"] = $request->post("postalcode");
        $data["province"] = $request->post("province");
        $data["locality"] = $request->post("locality");
        $data["number"] = (int)$request->post("number");
        if ($request->post("floor") && $request->post("floor") !== "") {
            $data["floor"] = (int)$request->post("floor");
        } else {
            $data["floor"] = null;
        }
        if ($request->post("apartment") && $request->post("apartment") !== "") {
            $data["apartment"] = (int)$request->post("apartment");
        } else {
            $data["apartment"] = null;
        }

        $this->addressRepository->create($data);

        echo $this->render('user/confirm_address.view.twig', "Confirm address", $request, ["user" => $request->user()]);
    }
}