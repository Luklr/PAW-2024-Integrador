<?php

namespace Paw\App\Controllers;
use Paw\Core\Request;
use Paw\App\Repositories\UserRepository;
use Twig\Environment;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(Environment $twig) {
        parent::__construct($twig);
        $this->userRepository = UserRepository::getInstance();
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
        $this->register($request, $mensaje);
    }

    public function account(Request $request) {
        if (!$request->session()->isLogged()) {
            $request->session()->set("loopback", $originPath);
            $this->redirect("/login");
        } 
        echo $this->render('user/account.view.twig', "Account", $request, ["user" => $request->user()]);
    }

}