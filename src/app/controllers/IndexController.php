<?php

namespace Paw\App\Controllers;
use Paw\Core\Request;
use Twig\Environment;

class IndexController extends Controller
{
    private $twig;

    public function __construct(Environment $twig) {
        $this->twig = $twig;
    }

    public function index(Request $request) {
        $title = "PC Fusion";
        echo $this->twig->render('index.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }
}