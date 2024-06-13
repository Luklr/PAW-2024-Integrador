<?php

namespace Paw\App\Controllers;
use Paw\Core\Request;

class IntranetController extends Controller
{
    public function createProduct(Request $request) {
        $data = ["type" => "Motherboard"];
        $this->render('intranet/create_product.view.twig', "Create Product", $request, $data);
    }
}