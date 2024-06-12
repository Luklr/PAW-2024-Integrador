<?php

namespace Paw\App\Controllers;
use Paw\Core\Request;

class UserController extends Controller
{
    public function login(Request $request) {
        if ($request->session()->isLogged()) {
            $this->redirect("/");
            return;
        }
        echo $this->render('user/login.view.twig', "Log in", $request);
    }

    public function loginPost(Request $request) {
        if ($request->session()->isLogged()) {
            $this->redirect("/");
            return;
        }
        // Lo logeamos
        $request->session()->set("user_id", 1);   // Temporal
        $request->session()->set("user_role", "user");
        
        // Verifico si tenía que redirigir, sino al home
        $originPath = $request->session()->get("loopback");
        if ($originPath) {
            $request->session()->delete("loopback");
            $this->redirect($originPath);
        } 
        else {
            $this->redirect("/");
        }
    }

    public function signin(Request $request) {
        echo $this->render('user/signin.view.twig', "Sign in", $request);
    }

    public function signinPost(Request $request) {

        $originPath = $request->session()->get("loopback");
        if (!$originPath) {
            $request->session()->delete("loopback");
            $this->redirect("/");
        } 
        else {
            $this->redirect($originPath);
        }
    }

    public function account(Request $request) {
        //$this->redirectIfNotLogged($request, "/account");
        if (!$request->session()->isLogged()) {
            $request->session()->set("loopback", $originPath);
            $this->redirect("/login");
        } 
        echo $this->render('user/account.view.twig', "Account", $request);
    }
}