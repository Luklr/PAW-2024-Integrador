<?php

namespace Paw\App\Controllers;
use Paw\Core\Request;

class PaymentController extends Controller
{

    public function branchSelection(Request $request) {
        echo $this->render('payment/branch_selection.view.twig', "Branch selection", $request);
    }

    public function cart(Request $request) {
        echo $this->render('payment/cart.view.twig', "Cart", $request);
    }

    public function confirmOrder(Request $request) {
        echo $this->render('payment/confirm_order.view.twig', "Confirm order", $request);
    }

    public function enterAddress(Request $request) {
        echo $this->render('payment/enter_address.view.twig', "Enter address", $request);
    }

    public function orderPickUp(Request $request) {
        echo $this->render('payment/order_pickup.view.twig', "Order pick up", $request);
    }
}