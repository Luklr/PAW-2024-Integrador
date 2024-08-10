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
        // echo("<pre>");
        // var_dump($specificComponent);
        // die;
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

    function assemblePc(Request $request) {
        $this->render('assemblePc/assemble_pc.view.twig', "Assemble your PC", $request);
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

    function assemblePcPowerSuply(Request $request) {
        $this->render('assemblePc/assemble_pc_power_suply.view.twig', "Assemble your PC", $request);
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
        $itemsPerPage = 30;
        $products = $this->componentRepository->getPage($itemsPerPage, $page);

        header('Content-Type: application/json');
        echo json_encode($products);
    }
}