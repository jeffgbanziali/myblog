<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{

    private $entitymanager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entitymanager = $entityManager;
    }
    public function viewAction(Request $request,)
    {
        //affichage de mon formulaire
        $form = $this->createForm(ArticleType::class);
        //handleRequest permet de récupérer les données du formaulaire suite à la soumission
        $form->handleRequest($request);

        //on vérifie  dque le formulaire a été validé 

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            //persist() prépare les données à insérer dans la table
            $this->entitymanager->persist($article);
            // flush() fait l'insertion des données dans la table
            $this->entitymanager->flush();

            //afficher un message de succès 
            $this->addFlash("success", "L'article a bien été enregistré ");

            return $this->redirectToRoute("home");
        }
        return $this->render("contact/article.html.twig", ["form" => $form->createView()]);
    }

    public function modifyAction(ArticleRepository $articleRepository, $id, Request $request)
    {
        //récupération du contact via son id
        $article = $articleRepository->find($id);
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $this->entitymanager->persist($article);
            $this->entitymanager->flush();
            $this->addFlash("success", "this contact is modified");
            return $this->redirectToRoute("home");
        }

        return $this->render("contact/article.html.twig", ['form' => $form->createView()]);
    }

    public function deleteAction(ArticleRepository $articleRepository, $id)
    {

        $article = $articleRepository->find($id);

        if ($article) {

            $articleRepository->remove($article, $flush = true);
            $this->addFlash('success', 'this contact is delete');
            return $this->redirectToRoute('home');
        }
        return $this->redirectToRoute('home');
    }
}
