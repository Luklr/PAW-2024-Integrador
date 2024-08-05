<?php

namespace Paw\App\Controllers;
use Paw\Core\Request;
use Paw\App\Models\Cart;
use Paw\App\Models\Components\Component;
use Paw\App\Repositories\ComponentRepository;
use Twig\Environment;

class PaymentController extends Controller
{
    private $componentRepository;

    public function __construct(Environment $twig) {
        parent::__construct($twig);
        $this->componentRepository = ComponentRepository::getInstance();
    }

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

    public function addComponentToCart(Request $request){
        $json = file_get_contents('php://input');

        $data = json_decode($json, true);

        if ($data === null) {
            http_response_code(400);
            echo json_encode(['error' => 'Bad Request: Invalid JSON']);
        } elseif (!isset($data['component_id']) || !isset($data['quantity'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Bad Request: Missing id or quantity']);
        } else {
            // Procesar los datos recibidos
            $id = $data['component_id'];
            $quantity = $data['quantity'];
            $cart = null;
            $session = $request->session();
            if (!$session->get("cart")){
                $cart = new Cart([]);
            } else {
                $cart = $session->get("cart");
            }
            if ($request->user() && !$cart->getUser()){
                $cart->setUser($request->user());
            }

            $component = $this->componentRepository->getByIdAndType($data['component_id']);
            $cart->addComponent($component, $data["quantity"]);
            $session->set("cart", $cart);

            // Aquí puedes añadir lógica para agregar el producto al carrito
            http_response_code(200);
            echo json_encode(['success' => true, 'id' => $id, 'quantity' => $quantity]);
        }
    }
}