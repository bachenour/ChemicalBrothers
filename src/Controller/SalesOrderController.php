<?php

namespace App\Controller;

use App\Entity\SalesOrder;
use App\Entity\OrderProduct;
use App\Repository\SalesOrderRepository;
use App\Form\SalesOrderType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CartRepository;
use App\Repository\CartProductRepository;
use App\Repository\ProductRepository;
use App\Repository\OrderProductRepository;

#[Route('/sales/order')]
class SalesOrderController extends AbstractController
{
    private $cartRepository;
    private $cartProductRepository;
    private $orderProductRepo;

    public function __construct(CartRepository $cartRepository, CartProductRepository $cartProductRepository, OrderProductRepository $orderProductRepo)
    {
        $this->cartRepository  = $cartRepository;
        $this->cartProductRepository  = $cartProductRepository;
        $this->orderProductRepo = $orderProductRepo;
    }


    #[Route('/', name: 'app_sales_order_index', methods: ['GET'])]
    public function index(SalesOrderRepository $salesOrderRepository): Response
    {
        return $this->render('sales_order/index.html.twig', [
            'sales_orders' => $salesOrderRepository->findAll(),
        ]);
    }

    #[Route('/userorders', name: 'app_sales_order_user', methods: ['GET'])]
    public function userOrders(SalesOrderRepository $salesOrderRepository): Response
    {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('sales_order/index.html.twig', [
            'sales_orders' => $salesOrderRepository->findBy(['user' => $user]),
        ]);
    }

    #[Route('/new', name: 'app_sales_order_new', methods: ['GET', 'POST'])]
    public function new(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        //create a new sales order
        $salesOrder = new SalesOrder();
        $salesOrder->setUser($user);
        $salesOrder->setDateOrder(new DateTime());
        // TODO add 7 days 
        $salesOrder->setDateDelivery(new DateTime());
        $entityManager->persist($salesOrder);
        $entityManager->flush();

        //create new lines in productOrder
        $cart = $this->cartRepository->findOneBy(['user' => $user, 'saved' => false]);
        if(!$cart){
            return $this->redirectToRoute('home');
        }
        $cartProducts = $this->cartProductRepository->findBy(['cart' => $cart]);
        foreach($cartProducts as $cartProduct){
            $product = $cartProduct->getProduct();
            $orderProduct = new OrderProduct();
            $orderProduct->setProduct($product);
            $orderProduct->setSalesOrder($salesOrder);
            //TODO update with the real quantity
            $orderProduct->setQuantity($cartProduct->getQuantity());
            
            $total = $cartProduct->getQuantity() * $product->getPrice();
            $orderProduct->setTotal($total);

            $entityManager->persist($orderProduct);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sales_order_user', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_sales_order_show', methods: ['GET'])]
    public function show(SalesOrder $salesOrder, EntityManagerInterface $entityManager): Response
    {
        $orderProductRepository = $entityManager->getRepository(OrderProduct::class);
        $orderProducts = $orderProductRepository->findBy(['salesOrder' => $salesOrder]);

        $products = [];

        foreach ($orderProducts as $orderProduct) {
            $product = $orderProduct->getProduct();
            array_push($products, $product);
        }

        return $this->render('sales_order/show.html.twig', [
            'sales_order' => $salesOrder,
            'products' => $products
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sales_order_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SalesOrder $salesOrder, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SalesOrderType::class, $salesOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_sales_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sales_order/edit.html.twig', [
            'sales_order' => $salesOrder,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sales_order_delete', methods: ['POST'])]
    public function delete(Request $request, SalesOrder $salesOrder, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$salesOrder->getId(), $request->request->get('_token'))) {
            $entityManager->remove($salesOrder);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sales_order_index', [], Response::HTTP_SEE_OTHER);
    }
}
