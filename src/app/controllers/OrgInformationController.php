<?php

namespace Paw\App\Controllers;
use Paw\Core\Request;
use Twig\Environment;
use Paw\App\Repositories\ProductoRepository;

class OrgInformationController extends Controller
{
    private $twig;

    public function __construct(Environment $twig) {
        $this->twig = $twig;
    }

    public function aboutUs(Request $request) {
        $title = "About us";
        echo $this->twig->render('about_us.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }

    public function branches(Request $request) {
        $title = "Branches";
        echo $this->twig->render('branches.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }

    public function consumerDefense(Request $request) {
        $title = "Consumer defense";
        echo $this->twig->render('consumer_defense.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }

    public function contacts(Request $request) {
        $title = "Contacts";
        echo $this->twig->render('contacts.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }

    public function news(Request $request) {
        $title = "News";
        echo $this->twig->render('news.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }
}