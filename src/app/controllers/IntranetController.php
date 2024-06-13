<?php

namespace Paw\App\Controllers;
use Paw\Core\Request;
use Paw\App\Models\Components\Component;
use Paw\App\Repositories\ComponentRepository;
use Twig\Environment;

class IntranetController extends Controller
{
    private $componentRepository;

    public function __construct(Environment $twig) {
        parent::__construct($twig);
        $this->componentRepository = ComponentRepository::getInstance();
    }

    public function createProduct(Request $request, $mensaje="") {
        $this->access($request, $request->url(), "admin");
        $type = $request->get("type");
        if (!$type) {
            $type = "";
        }

        $types = ["videoCard","motherboard","memory","internalHardDrive","cpuFan","monitor","casePc","powerSuply"];
        $data = [
            "type" => $type, 
            "mensaje" => $mensaje,
        
            "types" => $types];
        $this->render('intranet/create_product.view.twig', "Create Product", $request, $data);
    }

    public function createProductPost(Request $request) {
        $this->access($request, $request->url(), "admin");
        $type = $request->post("componentType");
        if (!$type) {
            $type = "";
        }
        
        $class = "Paw\\App\\Models\\Components\\$type";
        $component = new $class([]);
        $keys = $component->getKeys();

        $values = $request->post();
        unset($values["componentType"]);
        //$mother = $this->componentRepository->create($data, "Motherboard");

        if (!$request->hasBodyParams($keys)){
            //var_dump($request->get("type"));die;
            $this->createProduct($request, "Ingrese todos los campos requeridos");
            exit();
        }
        $component = $this->componentRepository->create($values, ucfirst($type));
        $this->createProduct($request, "Producto dado de alta");
    }

}