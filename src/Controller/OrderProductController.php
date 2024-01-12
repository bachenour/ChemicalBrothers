<?php

namespace App\Controller;

use App\Entity\OrderProduct;
use App\Form\OrderProduct1Type;
use App\Repository\OrderProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/order/product')]
class OrderProductController extends AbstractController
{
    #[Route('/', name: 'app_order_product_index', methods: ['GET'])]
    public function index(OrderProductRepository $orderProductRepository): Response
    {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('order_product/index.html.twig', [
            'order_products' => $orderProductRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_order_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $orderProduct = new OrderProduct();
        $form = $this->createForm(OrderProduct1Type::class, $orderProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($orderProduct);
            $entityManager->flush();

            return $this->redirectToRoute('app_order_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('order_product/new.html.twig', [
            'order_product' => $orderProduct,
            'form' => $form,
        ]);
    }

    #[Route('/{salesOrder}', name: 'app_order_product_show', methods: ['GET'])]
    public function show(OrderProduct $orderProduct): Response
    {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('order_product/show.html.twig', [
            'order_product' => $orderProduct,
        ]);
    }

    #[Route('/{salesOrder}/edit', name: 'app_order_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, OrderProduct $orderProduct, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(OrderProduct1Type::class, $orderProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_order_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('order_product/edit.html.twig', [
            'order_product' => $orderProduct,
            'form' => $form,
        ]);
    }

    #[Route('/{salesOrder}', name: 'app_order_product_delete', methods: ['POST'])]
    public function delete(Request $request, OrderProduct $orderProduct, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        
        if ($this->isCsrfTokenValid('delete'.$orderProduct->getSalesOrder(), $request->request->get('_token'))) {
            $entityManager->remove($orderProduct);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_order_product_index', [], Response::HTTP_SEE_OTHER);
    }
}
