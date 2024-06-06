<?php

namespace Paw\App\Controllers;
use Paw\Core\Request;
use Twig\Environment;

class AssemblePcController extends Controller
{
    private $twig;

    public function __construct(Environment $twig) {
        $this->twig = $twig;
    }

    public function products(Request $request) {
        $title = "Products";
        echo $this->twig->render('assemblePc/products.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }

    public function product(Request $request) {
        $title = ""; //nombre del producto!! -> /product?p=i9-9000 x ejemplo
        echo $this->twig->render('assemblePc/product.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }

    function assemblePc(Request $request) {
        $title = "Assemble your PC";
        echo $this->twig->render('assemblePc/assemble_pc.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }

    function assemblePcCase(Request $request) {
        $title = "Assemble your PC";
        echo $this->twig->render('assemblePc/assemble_pc_case.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }

    function assemblePcCpu(Request $request) {
        $title = "Assemble your PC";
        echo $this->twig->render('assemblePc/assemble_pc_cpu.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }

    function assemblePcGpu(Request $request) {
        $title = "Assemble your PC";
        echo $this->twig->render('assemblePc/assemble_pc_gpu.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }

    function assemblePcRam(Request $request) {
        $title = "Assemble your PC";
        echo $this->twig->render('assemblePc/assemble_pc_ram.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }

    function assemblePcMotherboard(Request $request) {
        $title = "Assemble your PC";
        echo $this->twig->render('assemblePc/assemble_pc_motherboard.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }

    function assemblePcDisk(Request $request) {
        $title = "Assemble your PC";
        echo $this->twig->render('assemblePc/assemble_pc_disk.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }

    function assemblePcPowerSuply(Request $request) {
        $title = "Assemble your PC";
        echo $this->twig->render('assemblePc/assemble_pc_power_suply.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }

    function assemblePcTemplates(Request $request) {
        $title = "Templates";
        echo $this->twig->render('assemblePc/template.view.twig', [
            'nav' => $this->nav,
            'footer' => $this->footer,
            'title' => $title,
        ]);
    }
}