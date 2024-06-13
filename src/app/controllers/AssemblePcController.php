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
        /*
        $data = [
            "id" => 2,
            "description" => "saasasdds",
            "price" => 7,
            "stock" => 50,
            "socket" => "jeje",
            "memory_slot" => 5,
        ];
        $mother = $this->componentRepository->create($data, "Motherboard");
        */
        $this->render('assemblePc/products.view.twig', "Products", $request);
    }

    public function product(Request $request) {
        $this->render('assemblePc/product.view.twig', "", $request);
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
}