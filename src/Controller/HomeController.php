<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class HomeController extends AbstractController
{
    public function indexAction(ContactRepository $contactRepository)
    {
        $contacts = $contactRepository->findAll();

        return $this->render("home.html.twig", ["contacts" => $contacts]);
    }
}
