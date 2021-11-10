<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{
    /**
      * displaying list categories
     * @Route("/categorie", name="list_categorie")
     */
    public function index(CategorieRepository $repo): Response
    {
        $categorie = $repo->findAll();
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
            'categorie' => $categorie
        ]);
    }
   

     /**
      * create and update categories
     * @Route("categoriecreate", name="categorie_create")
     * @Route("/categorie{id}edit", name="categorie_edit")
     */
    public function form( Categorie $categorie = null, Request $request, EntityManagerInterface $entityManager)
    {
        if(!$categorie){
            $categorie = new Categorie;
        }
        
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

      $entityManager->persist($categorie);
      $entityManager->flush();  

    }
        return $this->render('categorie/create.html.twig', [
            'formCategorie' => $form->createView(),
            'editMode' => $categorie->getId() !== null
        ]);
    }

    /**
      * delete categories
     * @Route("/deletecategorie{id}", name="categorie_delete")
     */
    public function delete_categorie( categorie $categorie)
    {
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($categorie);
        $entityManager->flush();

        return $this->redirectToRoute('list_categorie');
    }

    /**
      * showing categorie
     * @Route("/categorie{id}", name="categorie_show")
     */
    public function show_categorie(CategorieRepository $repo,$id): Response
    {
        $categorie = $repo->find($id);
        return $this->render('categorie/show.html.twig', [
            "categorie" => $categorie

        ]);
    }
  
}
