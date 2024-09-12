<?php
namespace Paw\App\Controllers;
use Paw\Core\Request;
use Paw\App\Models\Components\Component;
use Paw\App\Models\Components\Motherboard;
use Paw\App\Repositories\ComponentRepository;
use Twig\Environment;

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

    function stockById(Request $request) {
        if (!$request->get("id")){
            http_response_code(400);
            echo json_encode(['error' => 'Bad Request: Missing id']);
        } else {
            $id = $request->get("id");
            $stock = $this->componentRepository->getStockById($id);
            echo json_encode(["success" => true, "stock" => $stock]);
        }
    }

    function assemblePc(Request $request, bool $messageBoolean = false) {
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
        $this->render('assemblePc/assemble_pc.view.twig', "Assemble your PC", $request, 
        ["components" => $components, "messageBoolean" => $messageBoolean]);
    }

    function verifyAssemblePcNextComponent(Request $request) {
        $componentsOrder = [
            1 => "cpu",
            2 => "motherboard",
            3 => "memory",
            4 => "videoCard",
            5 => "internalHardDrive",
            6 => "cpuFan",
            7 => "powerSupply",
            8 => "casePc"
        ];

        if (!$request->get("type")) {
            http_response_code(400);
            echo json_encode(["error" => "Bad Request: Missing param 'type'"]);
            return;
        }

        $currentType = $request->get("type");
        $currentIndex = array_search($currentType, $componentsOrder);

        // Si no encuentra el tipo, retorna un error (400)
        if ($currentIndex === false) {
            http_response_code(400);
            echo json_encode(["error" => "Bad Request: invalid type"]);
            return;
        }

        // Si el índice no es el primero, verifica que el componente anterior esté en la sesión
        if ($currentIndex > 1) {
            $previousType = $componentsOrder[$currentIndex - 1];

            // Verifica si el componente anterior está en la sesión
            if (!$request->session()->get("AyPC_" . $previousType)) {
                http_response_code(400);
                echo json_encode(["error" => "Bad Request: You must select the previous component: $previousType"]);
                return;
            }
        }

        // Si todo está bien, retorna un código 200
        http_response_code(200);
        echo json_encode(["success" => true]);
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

    function templates(Request $request) {
        $this->render('assemblePc/templates.view.twig', "Assemble your PC", $request);
    }

    function template(Request $request) {
        $this->render('assemblePc/template.view.twig', "", $request);
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