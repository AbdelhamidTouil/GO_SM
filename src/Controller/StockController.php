<?php

namespace App\Controller;
use App\Entity\Stock;
use App\Form\StockType;
use App\Repository\StockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StockController extends AbstractController
{
    /**
     * dispalaying list sock
     * @Route("/stock", name="list_stock")
     */
    public function index( Request $request, PaginatorInterface $paginator,  StockRepository $repo)
    {
        $stock = $repo->findAll();
        $stock = $paginator->paginate(
        $stock,
        $request->query->getInt('page',1),
        3
        );
        return $this->render('stock/index.html.twig', [
            'controller_name' => 'StockController',
            'stock' =>$stock
        ]);
    }
     /**
      * create and update stock
     * @Route("stockcreate", name="stock_create")
     * @Route("/stock{id}/edit", name="stock_edit")
     */
    public function form( stock $stock = null, Request $request, EntityManagerInterface $entityManager)
    {
        if(!$stock){
            $stock = new stock;
        }
        
        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

      $entityManager->persist($stock);
      $entityManager->flush();  

    }
        return $this->render('stock/create.html.twig', [
            'formStock' => $form->createView(),
            'editMode' => $stock->getId() !== null
        ]);
    }

    /**
      * delete product
     * @Route("/deletestock{id}", name="stock_delete")
     */
    public function delete_stock( stock $stock)
    {
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($stock);
        $entityManager->flush();

        return $this->redirectToRoute('list_stock');
    }

    /**
       * show stock
     * @Route("/stock{id}", name="stock_show")
     */
    public function show_stock(stockRepository $repo,$id): Response
    {
        $stock = $repo->find($id);
        return $this->render('stock/show.html.twig', [
            "stock" => $stock

        ]);
    }
}
