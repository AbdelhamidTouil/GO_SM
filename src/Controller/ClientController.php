<?php

namespace App\Controller;
use App\Entity\Client;
use App\Form\ClientType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientController extends AbstractController
{


    /**
     * @Route("/client", name="list_client")
     */
    public function index(ClientRepository $repo)
    {
        $client =$repo->findAll();
       
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
            'client' =>$client
        ]);
    }

     
     /**
      * create and update client
     * @Route("clientcreate", name="client_create")
     * @Route("/client{id}edit", name="client_edit")
     */
    public function form( client $client = null, Request $request, EntityManagerInterface $entityManager)
    {
        if(!$client){
            $client = new client;
        }
        
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

      $entityManager->persist($client);
      $entityManager->flush();  

    }
        return $this->render('client/create.html.twig', [
            'formClient' => $form->createView(),
            'editMode' => $client->getId() !== null
        ]);
    }
    /**
      * delete clients
     * @Route("/deleteclient{id}", name="client_delete")
     */
    public function delete_client( client $client)
    {
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($client);
        $entityManager->flush();

        return $this->redirectToRoute('list_client');
    }

    /**
      * showing client
     * @Route("/client{id}", name="client_show")
     */
    public function show_client(ClientRepository $repo,$id): Response
    {
        $client = $repo->find($id);
        return $this->render('client/show.html.twig', [
            "client" => $client

        ]);
    }
}
