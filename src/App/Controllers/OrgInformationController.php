<?php

namespace Paw\App\Controllers;
use Paw\Core\Request;

class OrgInformationController extends Controller
{
    public function aboutUs(Request $request) {
        echo $this->render('orgInformation/about_us.view.twig', "About us", $request);
    }

    public function branches(Request $request) {
        echo $this->render('orgInformation/branches.view.twig', "Branches", $request);
    }

    public function consumerDefense(Request $request) {
        echo $this->render('orgInformation/consumer_defense.view.twig', "Consumer defense", $request);
    }

    public function contacts(Request $request) {
        echo $this->render('orgInformation/contacts.view.twig', "Contacts", $request, ["msg"=>False]);
    }

    public function contactsForm(Request $request) {
        if (!$request->isPost()) {
            echo $this->render('orgInformation/contacts.view.twig', "Contacts", $request, ["msg"=>False]);
            exit;
        }
        $errors = [];
        $nombres = trim($request->post('nombres'));
        $apellidos = trim($request->post('apellidos'));
        $email = trim($request->post('email'));
        $asunto = trim($request->post('asunto'));
        $mensaje = trim($request->post('mensaje'));

        if (empty($nombres)) {
            $errors['nombres'] = 'El campo nombres es obligatorio.';
        }

        if (empty($apellidos)) {
            $errors['apellidos'] = 'El campo apellidos es obligatorio.';
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'El campo correo electrónico es obligatorio y debe ser una dirección válida.';
        }

        if (empty($asunto)) {
            $errors['asunto'] = 'El campo asunto es obligatorio.';
        }

        if (empty($mensaje)) {
            $errors['mensaje'] = 'El campo detalles de la consulta es obligatorio.';
        }

        if (empty($errors)) {
            echo $this->render('orgInformation/contacts.view.twig', "Contacts", $request, ["msg"=>True]);
        } else {
            echo $this->render('orgInformation/contacts.view.twig', "Contacts", $request, ["msg"=>False]);
        }
    }
}