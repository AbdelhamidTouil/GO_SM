<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * displaying dashboord
     * @Route("/admin", name="dashbord")
     */
    public function index()
    {
        return $this->render('admin/dashbord.html.twig', [
            'controller_name' => 'AdminController',
            
        ]);
    }
}
