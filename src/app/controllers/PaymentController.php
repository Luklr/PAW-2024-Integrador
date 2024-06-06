<?php

namespace Paw\App\Controllers;
use Paw\Core\Request;
use Twig\Environment;

class PaymentController extends Controller
{
    private $twig;

    public function __construct(Environment $twig) {
        $this->twig = $twig;
    }

    public function branchSelection(Request $request) {
        $title = "Branch selection";
        echo $this->twig->render('payment/branch_selection.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }

    public function cart(Request $request) {
        $title = "Cart";
        echo $this->twig->render('payment/cart.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }

    public function confirmOrder(Request $request) {
        $title = "Confirm order";
        echo $this->twig->render('payment/confirm_order.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }

    public function enterAddress(Request $request) {
        $title = "Enter address";
        echo $this->twig->render('payment/enter_address.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }

    public function orderPickUp(Request $request) {
        $title = "Order pick up";
        echo $this->twig->render('payment/order_pickup.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }
}