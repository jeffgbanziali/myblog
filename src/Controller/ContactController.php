<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{

    private $entitymanager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entitymanager = $entityManager;
    }
    public function viewAction(Request $request,)
    {
        //affichage de mon formulaire
        $form = $this->createForm(ContactType::class);
        //handleRequest permet de récupérer les données du formaulaire suite à la soumission
        $form->handleRequest($request);

        //on vérifie  dque le formulaire a été validé 

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            //persist() prépare les données à insérer dans la table
            $this->entitymanager->persist($contact);
            // flush() fait l'insertion des données dans la table
            $this->entitymanager->flush();

            //afficher un message de succès 
            $this->addFlash("success", "Le message a bien été enregistré ");

            return $this->redirectToRoute("home");
        }
        return $this->render("contact/contact.html.twig", ["form" => $form->createView()]);
    }

    public function modifyAction(ContactRepository $contactRepository, $id, Request $request)
    {
        //récupération du contact via son id
        $contact = $contactRepository->find($id);
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $this->entitymanager->persist($contact);
            $this->entitymanager->flush();
            $this->addFlash("success", "this contact is modified");
            return $this->redirectToRoute("home");
        }

        return $this->render("contact/modify.html.twig", ['form' => $form->createView()]);
    }

    public function deleteAction(ContactRepository $contactRepository, $id)
    {

        $contact = $contactRepository->find($id);

        if ($contact) {

            $contactRepository->remove($contact, $flush = true);
            $this->addFlash('success', 'this contact is delete');
            return $this->redirectToRoute('home');
        }
        return $this->redirectToRoute('home');
    }
}
