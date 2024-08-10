<?php

namespace Paw\App\Controllers;
use Paw\Core\Request;
use Paw\App\Models\Cart;
use Paw\App\Models\Order;
use Paw\App\Models\Status;
use Paw\App\Models\Components\Component;
use Paw\App\Repositories\ComponentRepository;
use Paw\App\Repositories\OrderRepository;
use Twig\Environment;

class PaymentController extends Controller
{
    private $componentRepository;
    private $orderRepository;

    public function __construct(Environment $twig) {
        parent::__construct($twig);
        $this->componentRepository = ComponentRepository::getInstance();
        $this->orderRepository = OrderRepository::getInstance();
    }

    public function branchSelection(Request $request) {
        echo $this->render('payment/branch_selection.view.twig', "Branch selection", $request);
    }

    public function cart(Request $request) {
        $cart = null;
        $session = $request->session();
        $components = [];
        if ($session->get("cart")){
            $cart = $session->get("cart");
            $components = $cart->getComponents();
        }
        
        echo $this->render('payment/cart.view.twig', "Cart", $request, ["components" => $components]);
    }

    public function cartToOrder(Request $request) {
        $cart = null;
        $session = $request->session();
        $components = [];

        # verifico que tenga un carrito
        if (!$session->get("cart")) {
            echo $this->render('payment/cart.view.twig', "Cart", $request, ["components" => $components]);
            exit;
        }

        # me guardo el quantity-id para luego validar
        $cart = $session->get("cart");
        $components = $cart->getComponents();
        $idComponents = [];
        foreach ($components as $component){
            $idComponents[] = "quantity-" . $component["component"]->getId();
        }

        # valido que el post tenga todos los componentes, si falta alguno entonces esta incompleto
        if (!$request->hasBodyParams($idComponents)){
            echo $this->render('payment/cart.view.twig', "Cart", $request, ["components" => $components]);
            exit;
        }

        # edito las cantidades de cada componente en base al formulario
        foreach ($components as $component){
            $quantity = $request->post("quantity-" . $component["component"]->getId());
            $cart->editComponentQuantity($component, $quantity);
        }

        # creo el array para crear el order
        $data = [];
        $data["components"] = $components;
        $data["user"] = $cart->getUser();
        $data["orderDate"] = new DateTime();
        $price = 0;
        foreach ($components as $component) {
            $price += $component["quantity"] * $component["component"]->getPrice();
        }
        $data["orderPrice"] = $price;
        $data["status"] = Status::PENDING_PAYMENT;

        # creo la instancia en la bd
        $order = $orderRepository->create($data);

        # redirecciono a la pagina para que elija entre envio o ir a buscar a una sucursal
        header("Location: /order_pickup", true, 301);
        exit();
    }

    public function deleteItemCart(Request $request) {
        if (!$request->get("id")){
            http_response_code(400);
            echo json_encode(['error' => 'Bad Request: Missing id']);
        } elseif (!$request->session()->get("cart")) {
            http_response_code(400);
            echo json_encode(['error' => 'Bad Request: Missing items in the cart']);
        } else {
            $id = $request->get("id");
            $cart = $request->session()->get("cart");
            $component = $this->componentRepository->getByIdAndType($id);
            $cart->deleteComponent($component);
            http_response_code(200);
            echo json_encode(['success' => true, 'id' => $id]);
        }
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