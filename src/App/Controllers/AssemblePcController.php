<?php
namespace Paw\App\Controllers;
use Paw\Core\Request;
use Paw\App\Models\Components\Component;
use Paw\App\Models\Components\Motherboard;
use Paw\App\Repositories\ComponentRepository;
use Twig\Environment;
use Paw\App\Models\Cart;

class AssemblePcController extends Controller
{
    private $componentRepository;

    public function __construct(Environment $twig) {
        parent::__construct($twig);
        $this->componentRepository = ComponentRepository::getInstance();
    }

    public function products(Request $request) {
        $page = 0;
        $itemsPerPage = 10;
        $products = $this->componentRepository->getPage($itemsPerPage, $page); 

        
        $this->render('assemblePc/products.view.twig', "Products", $request, ["products" => $products]);
    }

    public function product(Request $request) {
        $id = $request->get("id");
        $component = $this->componentRepository->getByIdAndType($id);

        $specificComponent = $component->getSpecificComponent();
        $specificComponent = $specificComponent->toArray();
        
        $this->render('assemblePc/product.view.twig', $component->getDescription(), $request, ["product" => $component, "productSpecific" => $specificComponent]);
    }

    public function stockById(Request $request) {
        if (!$request->get("id")){
            http_response_code(400);
            echo json_encode(['error' => 'Bad Request: Missing id']);
        } else {
            $id = $request->get("id");
            $stock = $this->componentRepository->getStockById($id);
            echo json_encode(["success" => true, "stock" => $stock]);
        }
    }

    public function assemblePc(Request $request) {
        $components = null;
        $array = ["cpu","videoCard","memory","motherboard","internalHardDrive","cpuFan","powerSupply","casePc"];
        foreach ($array as $component){
            $components[$component] = [
                "path_img" => $request->session()->get("AyPC_" . $component) ? 
                ($request->session()->get("AyPC_" . $component)->getPath_img() ?: null) : null,
                "description" => $request->session()->get("AyPC_" . $component) ? 
                ($request->session()->get("AyPC_" . $component)->getDescription() ?: null) : null
            ];
        }

        // verifico que se haya agregado un nuevo producto,
        // en caso de que sea verdadero, se mostrará un mensaje de confirmación
        $messageBoolean = false;
        if ($request->session()->get("assemble_pc_new_product")){
            $messageBoolean = true;
            $request->session()->set("assemble_pc_new_product", false);
        }
        $this->render('assemblePc/assemble_pc.view.twig', "Assemble your PC", $request, 
        ["components" => $components, "messageBoolean" => $messageBoolean]);
    }

    public function assemblePcAddProduct(Request $request) {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'Bad Request: Invalid JSON']);
            exit;
        }
        if (!isset($data["id"]) || !isset($data["type"])) {
            http_response_code(400);
            echo json_encode(['error' => 'Bad Request: Missing params id or type']);
            exit;
        }

        $session = $request->session();

        if (!$this->verifyComponentOrder($request, $data["type"])){
            exit;
        }

        $component = $this->componentRepository->getByIdAndType($data["id"]);

        if (!$this->verifyCompatibility($request,$component)){
            exit;
        }
        $session->set("AyPC_" . $data["type"], $component);
        $this->addComponentToCart($request, $component);
        // CUANDO SE TERMINE LA CREACION DE LA PC, SE TIENEN QUE BORRAR TODOS!!!

        $session->set("assemble_pc_new_product", true);
        http_response_code(200);
        echo json_encode(['success' => true]);
        exit;
    }

    public function assemblePcDeleteProduct(Request $request){
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'Bad Request: Invalid JSON']);
            exit;
        }
        if (!isset($data["type"])) {
            http_response_code(400);
            echo json_encode(['error' => 'Bad Request: Missing params id or type']);
            exit;
        }

        $componentsStr = [
            1 => "cpu",
            2 => "motherboard",
            3 => "memory",
            4 => "videoCard",
            5 => "internalHardDrive",
            6 => "cpuFan",
            7 => "powerSupply",
            8 => "casePc"
        ];

        $session = $request->session();
        $currentType = $data["type"];
        $currentIndex = array_search($currentType, $componentsStr);

        // Si no encuentra el tipo, retorna un error (400)
        if ($currentIndex === false) {
            http_response_code(400);
            echo json_encode(["error" => "Bad Request: invalid type"]);
            exit;
        }

        for($i = $currentIndex; $i <= count($componentsStr); $i++)
        {
            if(!$session->get("AyPC_" . $componentsStr[$i]))
                continue;
            $component = $session->get("AyPC_" . $componentsStr[$i]);
            $session->delete("AyPC_" . $componentsStr[$i]);
            $this->delComponentFromCart($request, $component);
        }
        http_response_code(200);
        echo json_encode(['success' => true]);
        exit;
    }

    private function addComponentToCart(Request $request, Component $component){
        $session = $request->session();
        if (!$session->get("cart")){
            $cart = new Cart([]);
        } else {
            $cart = $session->get("cart");
        }

        if ($request->user() && !$cart->getUser()){
            $cart->setUser($request->user());
        }

        $quantity = 1;
        $cart->addComponent($component, $quantity);
        $session->set("cart", $cart);
    }

    private function delComponentFromCart(Request $request, Component $component){
        $session = $request->session();
        if (!$session->get("cart")){
            return;
        }
        $cart = $session->get("cart");

        $quantity = 1;
        $cart->deleteComponent($component);
        $session->set("cart", $cart);
    }

    private function verifyCompatibility(Request $request, Component $component): bool{
        $componentsStr = [
            1 => "cpu",
            2 => "motherboard",
            3 => "memory",
            4 => "videoCard",
            5 => "internalHardDrive",
            6 => "cpuFan",
            7 => "powerSupply",
            8 => "casePc"
        ];
        $session = $request->session();

        foreach($componentsStr as $compToVerifStr){
            if (!$session->get("AyPC_" . $compToVerifStr)){
                continue;
            }
            $compToverif = $session->get("AyPC_" . $compToVerifStr);
            
            if(!$compToverif->compatibleWith($component)){
                http_response_code(400);
                echo json_encode(['error' => "Bad Request: Component incompatible with " . $compToVerifStr]);
                return false;
            }
        }
        return true;
    }

    public function verifyAssemblePcNextComponent(Request $request) {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if ($data === null) {
            http_response_code(400);
            echo json_encode(['error' => 'Bad Request: Invalid JSON']);
            exit;
        }
        if (!isset($data["type"])) {
            http_response_code(400);
            echo json_encode(["error" => "Bad Request: Missing param 'type'"]);
            exit;
        }
        if ($this->verifyComponentOrder($request, $data["type"])){
            // Si todo está bien, retorna un código 200
            http_response_code(200);
            echo json_encode(["success" => true]);
        }
    }

    private function verifyComponentOrder(Request $request, string $type): bool{
        $componentsOrder = [
            1 => "cpu",
            2 => "motherboard",
            3 => "memory",
            4 => "internalHardDrive",
            5 => "powerSupply",
            6 => "casePc"
        ];

        $currentType = $type;
        $currentIndex = array_search($currentType, $componentsOrder);

        // Si no encuentra el tipo, retorna un error (400)
        if ($currentIndex === false) {
            http_response_code(400);
            echo json_encode(["error" => "Bad Request: invalid type"]);
            return false;
        }

        // Si el índice no es el primero, verifica que el componente anterior esté en la sesión
        if ($currentIndex > 1) {
            $previousType = $componentsOrder[$currentIndex - 1];
            // Verifica si el componente anterior está en la sesión
            if (!$request->session()->get("AyPC_" . $previousType)) {
                http_response_code(400);
                echo json_encode(["error" => "Bad Request: You must select the previous component: $previousType"]);
                return false;
            }
        }
        return true;
    }

    function completePc(Request $request){
        $allComponents = [
            1 => "cpu",
            2 => "motherboard",
            3 => "memory",
            4 => "videoCard",
            5 => "internalHardDrive",
            6 => "cpuFan",
            7 => "powerSupply",
            8 => "casePc"
        ];
        
        foreach($allComponents as $component){
            if (!$request->session()->get("AyPC_" . $component) 
            && $component<>"videoCard" && $component<>"cpuFan") {
                http_response_code(400);
                echo json_encode(["error" => "Bad Request: You must select all components"]);
                exit;
            }
        }

        foreach($allComponents as $component){
            # Elimino todos los componentes de la sesión, para que
            # pueda elegir otra PC sin perder los componentes de esta
            # de su carrito
            $request->session()->delete("AyPC_" . $component);
        }
        http_response_code(200);
        echo json_encode(["success" => true]);
        exit;
    }

    function assemblePcCase(Request $request) {
        $this->render('assemblePc/assemble_pc_case.view.twig', "Assemble your PC", $request);
    }

    function assemblePcCpu(Request $request) {
        $this->render('assemblePc/assemble_pc_cpu.view.twig', "Assemble your PC", $request);
    }

    function assemblePcGpu(Request $request) {
        $this->render('assemblePc/assemble_pc_gpu.view.twig', "Assemble your PC", $request);
    }

    function assemblePcRam(Request $request) {
        $this->render('assemblePc/assemble_pc_ram.view.twig', "Assemble your PC", $request);
    }

    function assemblePcMotherboard(Request $request) {
        $this->render('assemblePc/assemble_pc_motherboard.view.twig', "Assemble your PC", $request);
    }

    function assemblePcDisk(Request $request) {
        $this->render('assemblePc/assemble_pc_disk.view.twig', "Assemble your PC", $request);
    }

    function assemblePcPowerSupply(Request $request) {
        $this->render('assemblePc/assemble_pc_power_supply.view.twig', "Assemble your PC", $request);
    }

    function assemblePcCpuFan(Request $request) {
        $this->render("assemblePc/assemble_pc_cpu_fan.view.twig", "Assemble your PC", $request);
    }

    function assemblePcCasePage(Request $request) {
        $get = $request->get();
        $page = isset($get['page']) ? (int)$get['page'] : 0;
        $query = isset($get['query']) ? $get['query'] : null;
        $itemsPerPage = 30;
        $type = null;
        if ($request->session()->get("AyPC_powerSupply")){
            $sessionPowerSupply = $request->session()->get("AyPC_powerSupply");
            $type = $sessionPowerSupply->getSpecificComponent()->getType();
        }
        $products = $this->componentRepository->getPageAyPcCasePc($itemsPerPage, $page, $type, $query);

        header('Content-Type: application/json');
        echo json_encode($products);
    }

    function assemblePcCpuPage(Request $request) {
        $get = $request->get();
        $page = isset($get['page']) ? (int)$get['page'] : 0;
        $query = isset($get['query']) ? $get['query'] : null;
        $itemsPerPage = 30;
        $products = $this->componentRepository->getPageAyPcCpu($itemsPerPage, $page, null, $query);

        header('Content-Type: application/json');
        echo json_encode($products);
    }

    function assemblePcGpuPage(Request $request) {
        $get = $request->get();
        $page = isset($get['page']) ? (int)$get['page'] : 0;
        $query = isset($get['query']) ? $get['query'] : null;
        $itemsPerPage = 30;
        $products = $this->componentRepository->getPageAyPcVideoCard($itemsPerPage, $page, $query);

        header('Content-Type: application/json');
        echo json_encode($products);
    }

    function assemblePcRamPage(Request $request) {
        $get = $request->get();
        $page = isset($get['page']) ? (int)$get['page'] : 0;
        $query = isset($get['query']) ? $get['query'] : null;
        $itemsPerPage = 30;
        $sessionMotherboard = $request->session()->get("AyPC_motherboard");
        $memorySlots = $sessionMotherboard->getSpecificComponent()->getMemory_slots();
        
        $products = $this->componentRepository->getPageAyPcMemory($itemsPerPage, $page, $memorySlots, $query);

        header('Content-Type: application/json');
        echo json_encode($products);
    }

    function assemblePcMotherboardPage(Request $request) {
        $get = $request->get();
        $page = isset($get['page']) ? (int)$get['page'] : 0;
        $query = isset($get['query']) ? $get['query'] : null;
        $itemsPerPage = 30;
        $sessionCpu = $request->session()->get("AyPC_cpu");
        $socket = $sessionCpu->getSpecificComponent()->getSocket();
        $products = $this->componentRepository->getPageAyPcMotherboard($itemsPerPage, $page, $socket, null, $query);

        header('Content-Type: application/json');
        echo json_encode($products);
    }

    function assemblePcDiskPage(Request $request) {
        $get = $request->get();
        $page = isset($get['page']) ? (int)$get['page'] : 0;
        $query = isset($get['query']) ? $get['query'] : null;
        $itemsPerPage = 30;
        $products = $this->componentRepository->getPageAyPcInternalHardDrive($itemsPerPage, $page, $query);

        header('Content-Type: application/json');
        echo json_encode($products);
    }

    function assemblePcPowerSupplyPage(Request $request) {
        $get = $request->get();
        $page = isset($get['page']) ? (int)$get['page'] : 0;
        $query = isset($get['query']) ? $get['query'] : null;
        $itemsPerPage = 30;
        $type = null;
        if ($request->session()->get("AyPC_case")){
            $sessionCpu = $request->session()->get("AyPC_case");
            $type = $sessionCpu->getSpecificComponent()->getType();
        }
        $products = $this->componentRepository->getPageAyPcPowerSupply($itemsPerPage, $page, $type, $query);

        header('Content-Type: application/json');
        echo json_encode($products);
    }

    function assemblePcCpuFanPage(Request $request) {
        $get = $request->get();
        $page = isset($get['page']) ? (int)$get['page'] : 0;
        $query = isset($get['query']) ? $get['query'] : null;
        $itemsPerPage = 30;
        $products = $this->componentRepository->getPageAyPcCpuFan($itemsPerPage, $page, $query);

        header('Content-Type: application/json');
        echo json_encode($products);
    }

    function productsPage(Request $request) {
        $get = $request->get();
        $page = isset($get['page']) ? (int)$get['page'] : 0;
        $query = isset($_GET['query']) ? $_GET['query'] : null;
        $type = isset($_GET['type']) ? $_GET['type'] : null;
        $itemsPerPage = 30;
        $products = $this->componentRepository->getPage($itemsPerPage, $page, $query, $type);

        header('Content-Type: application/json');
        echo json_encode($products);
    }
}