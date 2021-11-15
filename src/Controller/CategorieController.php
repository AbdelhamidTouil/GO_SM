<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CategorieController extends AbstractController
{
    /**
      * displaying list categories
     * @Route("/categorie", name="list_categorie")
     */
    public function index( Request $request, PaginatorInterface $paginator,  CategorieRepository $repo)
    {
        $categorie = $repo->findAll();
        $categorie = $paginator->paginate(
        $categorie,
        $request->query->getInt('page',1),
        3
        );
        return $this->render('categorie/index.html.twig', [
            'categorie' =>$categorie
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
            $file = $categorie->getImage();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

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
