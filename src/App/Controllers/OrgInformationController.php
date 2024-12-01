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
        echo $this->render('orgInformation/contacts.view.twig', "Contacts", $request);
    }
}