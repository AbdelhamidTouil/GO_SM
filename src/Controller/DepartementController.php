<?php

namespace App\Controller;
use App\Entity\Departement;
use App\Form\DepartementType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DepartementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DepartementController extends AbstractController
{
    /**
     * @Route("/departement", name="list_departement")
     */
    public function index(DepartementRepository $repo)
    {
        $departement =$repo->findAll();
       
        return $this->render('departement/index.html.twig', [
            'controller_name' => 'DepartementController',
            'departement' =>$departement
        ]);
    }

     
     /**
      * create and update departement
     * @Route("departementcreate", name="departement_create")
     * @Route("/departement{id}edit", name="departement_edit")
     */
    public function form( Departement $departement = null, Request $request, EntityManagerInterface $entityManager)
    {
        if(!$departement){
            $departement = new departement;
        }
        
        $form = $this->createForm(DepartementType::class, $departement);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

      $entityManager->persist($departement);
      $entityManager->flush();  

    }
        return $this->render('departement/create.html.twig', [
            'formDepartement' => $form->createView(),
            'editMode' => $departement->getId() !== null
        ]);
    }
    /**
      * delete departements
     * @Route("/deletedepartement{id}", name="departement_delete")
     */
    public function delete_departement( departement $departement)
    {
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($departement);
        $entityManager->flush();

        return $this->redirectToRoute('list_departement');
    }

    /**
      * showing departement
     * @Route("/departement{id}", name="departement_show")
     */
    public function show_departement(DepartementRepository $repo,$id): Response
    {
        $departement = $repo->find($id);
        return $this->render('departement/show.html.twig', [
            "departement" => $departement

        ]);
    }
}
