<?php

namespace Paw\App\Controllers;
use Paw\Core\Request;
use Twig\Environment;
use Paw\App\Repositories\ProductoRepository;

class PaymentController extends Controller
{
    private $twig;

    public function __construct(Environment $twig) {
        $this->twig = $twig;
    }

    public function branchSelection(Request $request) {
        $title = "Branch selection";
        echo $this->twig->render('branch_selection.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }

    public function cart(Request $request) {
        $title = "Cart";
        echo $this->twig->render('cart.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }

    public function confirmOrder(Request $request) {
        $title = "Confirm order";
        echo $this->twig->render('confirm_order.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }

    public function enterAddress(Request $request) {
        $title = "Enter address";
        echo $this->twig->render('enter_address.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }

    public function orderPickUp(Request $request) {
        $title = "Order pick up";
        echo $this->twig->render('order_pickup.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }
}