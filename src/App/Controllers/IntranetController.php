<?php

namespace Paw\App\Controllers;

use Exception;
use Paw\App\Handlers\ImageHandler;
use Paw\Core\Request;
use Paw\App\Repositories\ComponentRepository;
use Paw\App\Repositories\NotificationRepository;
use Paw\App\Repositories\UserRepository;
use Paw\App\Models\Status;
use Paw\App\Models\Branch;
use Paw\App\Models\Address;
use Paw\App\Repositories\OrderRepository;
use Paw\App\Validators\RequestCreateProduct;
use Paw\Core\Exceptions\InvalidValueFormatException;
use Twig\Environment;
use Paw\App\Models\Components\Component;
use Paw\Core\Exceptions\InvalidStatusException;

class IntranetController extends Controller
{
    private $componentRepository;
    private $orderRepository;
    private $notificationRepository;
    private $userRepository;
    private ImageHandler $imageHandler;

    public function __construct(Environment $twig)
    {
        parent::__construct($twig);
        $this->componentRepository = ComponentRepository::getInstance();
        $this->orderRepository = OrderRepository::getInstance();
        $this->notificationRepository = NotificationRepository::getInstance();
        $this->userRepository = UserRepository::getInstance();
        $this->imageHandler = new ImageHandler($this->imagesDir);   # products/
    }

    public function managementOrders(Request $request){
        $this->access($request, $request->url(), "admin");
        $this->render('intranet/management_orders.view.twig', "Management Orders", $request);
    }

    public function managementOrder(Request $request){
        $this->access($request, $request->url(), "admin");
        $id = $request->get("order_id");
        $order = $this->orderRepository->getById($id);
        $this->render('intranet/management_order.view.twig', "Management Order", $request, ["order" => $order, "message" => null, "error" => false]);
    }

    public function getOrdersForManagement(Request $request){
        $this->access($request, $request->url(), "admin");
        # todas las ordenes menos en estado PENDING_PAYMENT y DELIVERED
        $ordersPreparing = $this->orderRepository->getOrdersForManagement(Status::PREPARING->label());
        $ordersDispatched = $this->orderRepository->getOrdersForManagement(Status::DISPATCHED->label());
        $ordersReadyForPickUp = $this->orderRepository->getOrdersForManagement(Status::READY_FOR_PICKUP->label());

        

        $ordersPreparingArray = [];
        $ordersDispatchedArray = [];
        $ordersReadyForPickUpArray = [];
        $orders = [];

        foreach($ordersPreparing as $order){
            $ordersPreparingArray[] = [
                "id" => $order->getId(),
                "order_date" => $order->getOrderdate(),
                "delivery_price" => $order->getDeliveryprice(),
                "user_id" => $order->getUser()->getId(),
                "branch" => $order->getBranch() ? $order->getBranch()->toArray() : null,
                "address" => $order->getAddress() ? $order->getAddress()->toArray() : null,
            ];
        }
        $orders[Status::PREPARING->label()] = $ordersPreparingArray;

        foreach($ordersDispatched as $order){
            $ordersDispatchedArray[] = [
                "id" => $order->getId(),
                "order_date" => $order->getOrderdate(),
                "user_id" => $order->getUser()->getId(),
                "branch" => $order->getBranch() ? $order->getBranch()->toArray() : null,
                "address" => $order->getAddress() ? $order->getAddress()->toArray() : null,
            ];
        }
        $orders[Status::DISPATCHED->label()] = $ordersDispatchedArray;

        foreach($ordersReadyForPickUp as $order){
            $ordersReadyForPickUpArray[] = [
                "id" => $order->getId(),
                "order_date" => $order->getOrderdate(),
                "user_id" => $order->getUser()->getId(),
                "branch" => $order->getBranch() ? $order->getBranch()->toArray() : null,
                "address" => $order->getAddress() ? $order->getAddress()->toArray() : null,
            ];
        }
        $orders[Status::READY_FOR_PICKUP->label()] = $ordersReadyForPickUpArray;

        http_response_code(200);
        echo json_encode(['orders' => $orders]);
    }

    public function setDeliveryPrice(Request $request) {
        $this->access($request, $request->url(), "admin");
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if(!$data){
            http_response_code(400);
            echo json_encode(['error' => 'Bad Request: Invalid JSON']);
            exit;
        }
        if(!$data["order_id"]){
            http_response_code(400);
            echo json_encode(['error' => "Bad Request: Missing order_id param"]);
            exit;
        }
        if (!$data["delivery_price"]){
            http_response_code(400);
            echo json_encode(['error' => "Bad Request: Missing delivery_price param"]);
            exit;
        }
        $orderId = $data["order_id"];
        $orderDeliveryPrice = floatval($data["delivery_price"]);
        $this->orderRepository->setDeliveryPrice($orderId,$orderDeliveryPrice);
        $message = "Delivery price setted";
        http_response_code(200);
        echo json_encode(['success' => true]);
    }

    public function setOrderStatus(Request $request) {
        $this->access($request, $request->url(), "admin");
        $json = file_get_contents('php://input');

        $data = json_decode($json, true);

        if ($data === null) {
            http_response_code(400);
            echo json_encode(['error' => 'Bad Request: Invalid JSON']);
            exit;
        }
        if (!isset($data['order_id']) || !isset($data['status'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Bad Request: Missing id or quantity']);
            exit;
        }

        $order = $this->orderRepository->getById($data["order_id"]);
        $statusNow = $order->getStatus();
        $statusNew = $data["status"];
        $notificationType = null;

        # verifica que el nuevo status no sea incorrecto
        try{
            switch($statusNew){
                case Status::PREPARING->label():
                    $order->prepare();
                    $notificationType = $this->notificationRepository->getNotificationTypeByName(Status::PREPARING->label());
                    break;
                case Status::DISPATCHED->label():
                    $order->dispatch();
                    $notificationType = $this->notificationRepository->getNotificationTypeByName(Status::DISPATCHED->label());
                    break;
                case Status::READY_FOR_PICKUP->label():
                    $order->readyForPickup();
                    $notificationType = $this->notificationRepository->getNotificationTypeByName(Status::READY_FOR_PICKUP->label());
                    break;
                case Status::DELIVERED->label():
                    $order->delivered();
                    $notificationType = $this->notificationRepository->getNotificationTypeByName(Status::DELIVERED->label());
                    break;
                default:
                    throw new InvalidStatusException("Invalid sent status");
            }
        } catch (InvalidStatusException $e){
            http_response_code(400);
            $message = $e->getMessage();
            echo json_encode(['error' => $message, "correction" => $statusNow]);
            exit;
        }
        
        $this->orderRepository->setStatus($order);
        $notificationArray =
        [
            "notification_type" => $notificationType,
            "order" => $order,
            "user" => $order->getUser(),
            "timestamp" => new \DateTime()
        ];
        $this->notificationRepository->create($notificationArray);
        http_response_code(200);
        echo json_encode(['success' => true]);
    }

    public function createProduct(Request $request, $mensaje="") {
        $this->access($request, $request->url(), "admin");
        $type = $request->get("type");
        if (!$type) {
            $type = "";
        }

        $types = ["videoCard","motherboard","memory","internalHardDrive","cpuFan","monitor","casePc","powerSupply"];
        $data = [
            "type" => $type, 
            "mensaje" => $mensaje,
            "types" => $types];
        $this->render('intranet/create_product.view.twig', "Create Product", $request, $data);
    }

    public function createProductPost(Request $request) {
        $this->access($request, $request->url(), "admin");
        $tipo = $request->post("componentType");
        if (!$tipo) {
            $tipo = "";
        }

        # REPENSAR ESTA LÓGICA, AUNQUE ESTÁ BIEN
        $type = ucfirst($tipo);
        $class = "Paw\\App\\Models\\Components\\$type";
        $specificComponent = new $class([]);
        $component = new Component([]);
        $componentKeys = array_merge($specificComponent->getKeys(), $component->getKeys());

        try {
            RequestCreateProduct::validate($request, $componentKeys);
            $values = $request->post();
            unset($values["componentType"]);       # REPENSAR ESTO
            $values['path_img'] = $this->imageHandler->saveImage($request->file("imagen"), 'images/components');
            $component = $this->componentRepository->create($values, $type);
            $mensaje = "El producto fue procesado y subido con éxito";
        } catch (InvalidValueFormatException $e) {
            $mensaje = $e->getMessage();
            dd($e->getMessage());
        } catch (Exception $e) {
            $mensaje = "Ocurrió un error al procesar su solicitud. " . $e->getMessage();
            dd($e->getMessage());
        }
        
        $this->createProduct($request, $mensaje);
    }

    public function products(Request $request){
        $this->access($request, $request->url(), "admin");
        $this->render('intranet/products.view.twig', "Products", $request);
    }

    public function product(Request $request) {
        $this->access($request, $request->url(), "admin");
        $id = $request->get("id");
        $component = $this->componentRepository->getByIdAndType($id);

        $specificComponent = $component->getSpecificComponent();
        $specificComponent = $specificComponent->toArray();
        
        $this->render('intranet/product.view.twig', $component->getDescription(), $request, ["product" => $component, "productSpecific" => $specificComponent]);
    }

    function productsPage(Request $request) {
        $this->access($request, $request->url(), "admin");
        $get = $request->get();
        $page = isset($get['page']) ? (int)$get['page'] : 0;
        $query = isset($_GET['query']) ? $_GET['query'] : null;
        $type = isset($_GET['type']) ? $_GET['type'] : null;
        $itemsPerPage = 30;
        $products = $this->componentRepository->getPage($itemsPerPage, $page, $query, $type);

        header('Content-Type: application/json');
        echo json_encode($products);
    }

    public function addComponentStock(Request $request){
        $this->access($request, $request->url(), "admin");
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
            
            
            $this->componentRepository->addStockById($id,$quantity);

            // Aquí puedes añadir lógica para agregar el producto al carrito
            http_response_code(200);
            echo json_encode(['success' => true, 'id' => $id, 'quantity' => $quantity]);
        }
    }

    public function reduceComponentStock(Request $request){
        $this->access($request, $request->url(), "admin");
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
            
            
            $this->componentRepository->reduceStockById($id,$quantity);

            // Aquí puedes añadir lógica para agregar el producto al carrito
            http_response_code(200);
            echo json_encode(['success' => true, 'id' => $id, 'quantity' => $quantity]);
        }
    }

    public function deleteComponent(Request $request){
        $this->access($request, $request->url(), "admin");
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if ($data === null) {
            http_response_code(400);
            echo json_encode(['error' => 'Bad Request: Invalid JSON']);
        } elseif (!isset($data['component_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Bad Request: Missing id']);
        } else {
            // Procesar los datos recibidos
            $id = $data['component_id'];
            
            
            $this->componentRepository->deleteById($id);

            // Aquí puedes añadir lógica para agregar el producto al carrito
            http_response_code(200);
            echo json_encode(['success' => true, 'id' => $id]);
        }
    }
}