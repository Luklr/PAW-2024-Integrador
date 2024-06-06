<?php

namespace Paw\App\Controllers;
use Paw\Core\Request;
use Twig\Environment;

class UserController extends Controller
{
    private $twig;

    public function __construct(Environment $twig) {
        $this->twig = $twig;
    }

    public function login(Request $request) {
        $title = "Login";
        echo $this->twig->render('user/login.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }

    public function signin(Request $request) {
        $title = "Signin";
        echo $this->twig->render('user/signin.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }

    public function account(Request $request) {
        $title = "Account";
        echo $this->twig->render('user/account.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }
}