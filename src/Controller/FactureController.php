<?php

namespace App\Controller;
use App\Entity\Facture;
use App\Form\FactureType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FactureRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FactureController extends AbstractController
{
    /**
     * @Route("/facture", name="list_facture")
     */
    public function index(FactureRepository $repo)
    {
        $facture =$repo->findAll();
       
        return $this->render('facture/index.html.twig', [
            'controller_name' => 'FactureController',
            'facture' =>$facture
        ]);
    }

     
     /**
      * create and update facture
     * @Route("facturecreate", name="facture_create")
     * @Route("/facture{id}edit", name="facture_edit")
     */
    public function form( facture $facture = null, Request $request, EntityManagerInterface $entityManager)
    {
        if(!$facture){
            $facture = new facture;
        }
        
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

      $entityManager->persist($facture);
      $entityManager->flush();  

    }
        return $this->render('facture/create.html.twig', [
            'formFacture' => $form->createView(),
            'editMode' => $facture->getId() !== null
        ]);
    }
    /**
      * delete factures
     * @Route("/deletefacture{id}", name="facture_delete")
     */
    public function delete_facture( facture $facture)
    {
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($facture);
        $entityManager->flush();

        return $this->redirectToRoute('list_facture');
    }

    /**
      * showing facture
     * @Route("/facture{id}", name="facture_show")
     */
    public function show_facture(FactureRepository $repo,$id): Response
    {
        $facture = $repo->find($id);
        return $this->render('facture/show.html.twig', [
            "facture" => $facture

        ]);
    }
}
