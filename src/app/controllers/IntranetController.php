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
        $types = ["video_card","motherboard","memory","internal_hard_drive","cpu_fan","monitor","case_pc","power_suply"];
        $data = [
            "type" => strtolower($type), 
            "types" => $types];
        $this->render('intranet/create_product.view.twig', "Create Product", $request, $data);
    }
}