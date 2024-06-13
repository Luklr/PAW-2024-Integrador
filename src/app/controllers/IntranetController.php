<?php

namespace Paw\App\Controllers;
use Paw\Core\Request;

class IntranetController extends Controller
{
    public function createProduct(Request $request) {
        $this->access($request, $request->url(), "admin");
        $type = $request->get("type");
        if (!$type) {
            $type = "";
        }

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

        $types = ["videoCard","motherboard","memory","internalHardDrive","cpuFan","monitor","casePc","powerSuply"];
        $data = [
            "type" => $type, 
            "types" => $types];
        $this->render('intranet/create_product.view.twig', "Create Product", $request, $data);
    }

    public function createProductPost(Request $request) {
        $this->access($request, $request->url(), "admin");
        $type = $request->post("type");
        if (!$type) {
            $type = "";
        }

        $values = $request->post();
        echo "<pre>";
        var_dump($values); die;
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
        if (!class_exists($type)) {
            $type = "Paw\\App\\Models\\Components\\$type";
        }
        $fields = $types::getKeys();
        if (!$request->hasBodyParams($fields)){

        }
        $types = ["VideoCard","Motherboard","Memory","InternalHardDrive","cpuFan","Monitor","CasePc","PowerSuply"];
        $data = [
            "type" => strtolower($type), 
            "types" => $types];
        $this->render('intranet/create_product.view.twig', "Create Product", $request, $data);
    }

}