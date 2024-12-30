<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    private $authorEntityManager;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->authorEntityManager = $doctrine->getManager('authors');
    }

    /*Fonction de recupération de tous les auteurs*/
    #[Route("/authors", name: "app_authors")]
    public function authors(ManagerRegistry $doctrine): Response
    {
        $authors = $doctrine->getRepository(Author::class, 'authors')->findAll();
        return $this->render('author/listesAuthors.html.twig', [
            'authors' => $authors
        ]);
    }

    /*Fonction d'ajout d'un auteur*/
    #[Route('/author/ajouter', name: 'app_ajouter_author')]
    public function ajouterAuthor(Request $request): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->authorEntityManager->persist($author);
            $this->authorEntityManager->flush();
            return $this->redirectToRoute('app_authors');
        }
        
        return $this->render('author/ajouterAuthor.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /*Fonction de modification d'un auteur*/
    #[Route("/author/modifier/{id<\\d+>}", name: "app_modifier_author")]
    public function modifierAuthor(Request $request, Author $author): Response
    {
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->authorEntityManager->flush();
            return $this->redirectToRoute('app_authors');
        }
        
        return $this->render('author/modifierAuthor.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /*Fonction de suppression d'un auteur*/
    #[Route("/author/supprimer/{id<\\d+>}", name: "app_supprimer_author")]
    public function supprimerAuthor(Author $author): Response
    {
        $this->authorEntityManager->remove($author);
        $this->authorEntityManager->flush();
        return $this->redirectToRoute('app_authors');
    }
}