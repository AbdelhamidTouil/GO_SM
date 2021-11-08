<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * displaying list users
     * @Route("/user", name="user_list")
     */
    public function index(UserRepository $repo)
    {
        $user = $repo->findAll();
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'user' =>$user
        ]);
    }
       /**
      * create and update product
     * @Route("user/create", name="user_create")
     * @Route("/user/{id}/edit", name="user_edit")
     */
    public function form( User $user = null, Request $request, EntityManagerInterface $entityManager)
    {
        if(!$user){
            $user = new user;
        }
        
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

      $entityManager->persist($user);
      $entityManager->flush();  

    }
        return $this->render('user/create.html.twig', [
            'formUser' => $form->createView(),
            'editMode' => $user->getId() !== null
        ]);
    }

    /**
      * delete product
     * @Route("/delete/user/{id}", name="user_delete")
     */
    public function delete_user( user $user)
    {
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('list_user');
    }

    /**
       * show user
     * @Route("/user/{id}", name="user_show")
     */
    public function show_user(UserRepository $repo,$id): Response
    {
        $user = $repo->find($id);
        return $this->render('user/show.html.twig', [
            "user" => $user

        ]);
    }
  
}


