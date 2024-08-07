<?php

namespace Paw\App\Controllers;

use Exception;
use Paw\App\Handlers\ImageHandler;
use Paw\Core\Request;
use Paw\App\Repositories\ComponentRepository;
use Paw\App\Validators\RequestCreateProduct;
use Paw\Core\Exceptions\InvalidValueFormatException;
use Twig\Environment;
use Paw\App\Models\Components\Component;

class IntranetController extends Controller
{
    private $componentRepository;
    private ImageHandler $imageHandler;

    public function __construct(Environment $twig)
    {
        parent::__construct($twig);
        $this->componentRepository = ComponentRepository::getInstance();
        $this->imageHandler = new ImageHandler($this->imagesDir);   # products/
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
            $values['path_img'] = $this->imageHandler->saveImage($request->file("imagen"), 'productos');
            $component = $this->componentRepository->create($values, $type);
            $mensaje = "El producto fue procesado y subido con éxito";
        } catch (InvalidValueFormatException $e) {
            $mensaje = $e->getMessage();
            //dd($e->getMessage());
        } catch (Exception $e) {
            $mensaje = "Ocurrió un error al procesar su solicitud. " . $e->getMessage();
            //dd($e->getMessage());
        }
        
        $this->createProduct($request, $mensaje);
    }

}