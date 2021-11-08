<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    /**
      * dispalaying list products
     * @Route("/produit", name="list_produit")
     */
    public function index(ProduitRepository $repo)
    {
        $produit = $repo->findAll();
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
            'produit' => $produit
        ]);
    }

     /**
      * create and update product
     * @Route("produit/create", name="produit_create")
     * @Route("/produit/{id}/edit", name="produit_edit")
     */
    public function form( Produit $produit = null, Request $request, EntityManagerInterface $entityManager)
    {
        if(!$produit){
            $produit = new Produit;
        }
        
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

      $entityManager->persist($produit);
      $entityManager->flush();  

    }
        return $this->render('produit/create.html.twig', [
            'formProduit' => $form->createView(),
            'editMode' => $produit->getId() !== null
        ]);
    }

    /**
      * delete product
     * @Route("/delete/produit/{id}", name="produit_delete")
     */
    public function delete_produit( produit $produit)
    {
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($produit);
        $entityManager->flush();

        return $this->redirectToRoute('list_produit');
    }

    /**
       * show product
     * @Route("/produit/{id}", name="produit_show")
     */
    public function show_produit(ProduitRepository $repo,$id): Response
    {
        $produit = $repo->find($id);
        return $this->render('produit/show.html.twig', [
            "produit" => $produit

        ]);
    }
  
}
