<?php

namespace Paw\App\Controllers;
use Paw\Core\Request;

class ErrorController extends Controller
{
    public function forbidden(Request $request) {
        http_response_code(403);
        $this->render('error/forbidden.view.twig', "PC Fusion", $request);
    }

    public function notFound(Request $request) {
        http_response_code(404);
        $this->render('error/not_found.view.twig', "PC Fusion", $request);
    }

    public function internalServerError(Request $request) {
        http_response_code(500);
        $this->render('error/server_error.view.twig', "PC Fusion", $request);
    }
}