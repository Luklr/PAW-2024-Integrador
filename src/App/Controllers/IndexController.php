<?php

namespace Paw\App\Controllers;
use Paw\Core\Request;
use Paw\App\Models\Components\Component;
use Paw\App\Models\Components\Motherboard;
use Paw\App\Repositories\ComponentRepository;
use Twig\Environment;

class IndexController extends Controller
{

    private $componentRepository;

    public function __construct(Environment $twig) {
        parent::__construct($twig);
        $this->componentRepository = ComponentRepository::getInstance();
    }

    public function index(Request $request) {
        $page = 0;
        $itemsPerPage = 3;
        $products = $this->componentRepository->getPage($itemsPerPage, $page);
        $this->render('index.view.twig', "Assembl", $request, ["products" => $products]);
    }
}