<?php

namespace Paw\App\Controllers;
use Paw\Core\Request;

class IndexController extends Controller
{
    public function index(Request $request) {
        $this->render('index.view.twig', "Assembl", $request);
    }
}