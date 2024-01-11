<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;

class HomeController extends AbstractController
{
    //#[Route('/home', name: 'app_home')]
    public function index(ProductRepository $productRep): Response
    {
        $products = $productRep->findAllProducts();

        return $this->render('home/index.html.twig', [
            'products' => $products,
        ]);
    }
}
