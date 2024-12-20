<?php

namespace Paw\App\Controllers;

use Paw\Core\Request;
use Paw\App\Models\Cart;
use Paw\App\Models\Order;
use Paw\App\Models\Status;
use Paw\App\Models\Components\Component;
use Paw\App\Repositories\ComponentRepository;
use Paw\App\Repositories\OrderRepository;
use Paw\App\Repositories\BranchRepository;
use Paw\App\Repositories\AddressRepository;
use Twig\Environment;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;


class PaymentController extends Controller
{
    private $componentRepository;
    private $orderRepository;
    private $branchRepository;
    private $addressRepository;

    public function __construct(Environment $twig) {
        parent::__construct($twig);
        $this->componentRepository = ComponentRepository::getInstance();
        $this->orderRepository = OrderRepository::getInstance();
        $this->branchRepository = BranchRepository::getInstance();
        $this->addressRepository = AddressRepository::getInstance();
    }

    public function branchSelection(Request $request) {
        $this->redirectIfNotLogged($request, "/");
        $session = $request->session();
        if(!$session->get("order_id")){
            $this->redirect("/cart");
        }
        $branches = $this->branchRepository->getAll();
        // echo "<pre>";
        // var_dump($branches);die;
        echo $this->render('payment/branch_selection.view.twig', "Branch selection", $request, ["branches" => $branches]);
    }

    public function enterAddress(Request $request) {
        $this->redirectIfNotLogged($request, "/");
        $session = $request->session();
        if(!$session->get("order_id")){
            $this->redirect("/cart");
        }
        $addresses = $this->addressRepository->getByUser($request->user()->getId());
        
        $request->session()->set("loopback", "/enter_address");
        echo $this->render('payment/enter_address.view.twig', "Enter address", $request, ["addresses" => $addresses]);
    }

    public function setBranchOrder(Request $request) {
        $session = $request->session();
        if(!$session->get("order_id")){
            http_response_code(400);
            echo json_encode(['message' => 'Bad Request: order not started']);
        }
        if(!$request->get("id")){
            http_response_code(400);
            echo json_encode(['message' => 'Bad Request: Missing id']);
        }
        $idBranch = $request->get("id");
        $idOrder = $session->get("order_id");
        # modifico el order, le pongo el id branch
        $this->orderRepository->setBranch($idOrder, $idBranch);
        http_response_code(200);
        echo json_encode(['success' => true, "branch_id" => $idBranch]);
    }

    public function setAddressOrder(Request $request) {
        $session = $request->session();
        if(!$session->get("order_id")){
            http_response_code(400);
            echo json_encode(['message' => 'Bad Request: order not started']);
        }
        if(!$request->get("id")){
            http_response_code(400);
            echo json_encode(['message' => 'Bad Request: Missing id']);
        }
        $idAddress = $request->get("id");
        $idOrder = $session->get("order_id");
        # modifico el order, le pongo el id branch
        $this->orderRepository->setAddress($idOrder, $idAddress);
        http_response_code(200);
        echo json_encode(['success' => true, "address_id" => $idAddress]);
    }

    public function cart(Request $request) {
        $this->redirectIfNotLogged($request, "/cart");
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
            $cart->editComponentQuantity($component["component"], $quantity);
        }

        # creo el array para crear el order
        $data = [];
        $data["components"] = $components;
        if ($cart->getUser()){
            $data["user"] = $cart->getUser();
        } else {
            $data["user"] = $request->user();
        }
        
        $data["orderdate"] = new \DateTime();
        $price = 0;
        foreach ($components as $component) {
            $price += $component["quantity"] * $component["component"]->getPrice();
        }
        $data["orderprice"] = $price;
        $data["status"] = Status::PENDING_PAYMENT;
        # creo la instancia en la bd
        $order = $this->orderRepository->create($data);
        $session->set("order_id", $order->getId());
        # redirecciono a la pagina para que elija entre envio o ir a buscar a una sucursal
        $session->unset("cart");
        $this->redirect("/order_pickup");
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
        $this->redirectIfNotLogged($request, "/");
        $session = $request->session();
        if(!$session->get("order_id")){
            $this->redirect("/cart");
        }
        $orderId = $session->get("order_id");
        $order = $this->orderRepository->getById($orderId);
        echo $this->render('payment/confirm_order.view.twig', "Confirm order", $request, ["order" => $order]);
    }

    public function confirmOrderMp(Request $request) {
        $this->redirectIfNotLogged($request, "/");
        $session = $request->session();
        if(!$session->get("order_id")){
            $this->redirect("/cart");
        }
        $orderId = $session->get("order_id");
        $this->orderRepository->confirmOrder($orderId);
        $session->unset("order_id");
        $this->redirect("/registered_order");
    }

    public function registeredOrder(Request $request){
        echo $this->render('payment/registered_order.view.twig', "Registered order", $request);
    }

    public function orderPickUp(Request $request) {
        $this->redirectIfNotLogged($request, "/registered_order");
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

    protected function authenticateMP()
    {
        // Getting the access token from .env file (create your own function)
        $mpAccessToken = getenv('MP_ACCESS_TOKEN');
        // Set the token the SDK's config
        MercadoPagoConfig::setAccessToken($mpAccessToken);
        // (Optional) Set the runtime enviroment to LOCAL if you want to test on localhost
        // Default value is set to SERVER
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
    }

    // Function that will return a request object to be sent to Mercado Pago API
    function createPreferenceRequest($items, $payer): array
    {
        $paymentMethods = [
            "excluded_payment_methods" => [],
            "installments" => 12,
            "default_installments" => 1
        ];
        
        // $back_domain = getenv('NGROK_URL');
        $back_domain = getenv('APP_URL');
        $backUrls = array(
            "success" => $back_domain . "/confirm_order_mp",
            "failure" => $back_domain . "/confirm_order",
            "pending" => $back_domain . "/confirm_order_mp"
        );

        $request = [
            "items" => $items,
            "payer" => $payer,
            "payment_methods" => $paymentMethods,
            "back_urls" => $backUrls,
            "statement_descriptor" => "NAME_DISPLAYED_IN_USER_BILLING",
            "external_reference" => "1234567890",
            "expires" => false,
            "auto_return" => 'approved',
        ];

        return $request;
    }

    # /create_preference
    public function createPreference(Request $request)
    {
        # source: https://github.com/mercadopago/checkout-payment-sample/blob/master/server/php/server.php
        $json = $request->getBody();
        $data = json_decode($json);
        $this->authenticateMP();

        // Crear una preferencia
        $items = [];
        foreach ($data as $product) {
            $items[] = [
            "title" => $product->description,
            "quantity" => $product->quantity,
            "unit_price" => $product->price
            ];
        }
        $user = $request->user();
        $payer = [
            "name" => $user->getName(),
            "surname" => $user->getLastname(),
            "email" => $user->getEmail(),
        ];

        $request = $this->createPreferenceRequest($items, $payer);
        $client = new PreferenceClient;

        try {
            // Send the request that will create the new preference for user's checkout flow
            $preference = $client->create($request);

            // Useful props you could use from this object is 'init_point' (URL to Checkout Pro) or the 'id'
            // echo json_encode($preference);

            // Redirigir al usuario a la URL de pago
            // header("Location: " . $preference->init_point);
            // exit;
            echo json_encode($preference);
        } catch (MPApiException $error) {
            // Here you might return whatever your app needs.
            // We are returning null here as an example.
            echo json_encode(["error" => $error->getMessage()]);
        }
    }
}