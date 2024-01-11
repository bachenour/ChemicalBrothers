<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use App\Repository\CartRepository;
use App\Repository\UserRepository;
use App\Repository\CartProductRepository;
use App\Entity\Cart;
use App\Entity\User;
use App\Entity\CartProduct;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class CartController extends AbstractController
{
    private $productRepository;
    private $userRepository;
    private $cartRepository;
    private $entityManager;
    private $cartProductRepository;

    public function __construct(ProductRepository $productRepository, CartRepository $cartRepository, EntityManagerInterface $entityManager, CartProductRepository $cartProductRepository, UserRepository $userRepository)
    {
        $this->productRepository = $productRepository;
        $this->cartProductRepository = $cartProductRepository;
        $this->cartRepository  = $cartRepository;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    /**
     * #[Route("/add-to-cart/{productId}", name="add_to_cart", methods={"POST"})
     */
    public function addToCart($productId): Response
    {
        $product = $this->productRepository->find($productId);

        if (!$product) {
            return $this->redirectToRoute('home');// RENVOYER VERS PAGE PRODUIT INEXISTANT
        }

        $cart = $this->cartRepository->findActiveCart();

        // A MODIFIER
        $user = $this->userRepository->find(1);

        if (!$cart) {
            $cart = new Cart();
            $cart->setUser($user);  //A MODIFIER
            $cart->setTotal(0);
            $this->entityManager->persist($cart);
            $this->entityManager->flush();
        }

         
        $cartProduct = $this->cartProductRepository->findOneBy(['cart' => $cart, 'product' => $product]);

        if ($cartProduct) {
            $cartProduct->setQuantity($cartProduct->getQuantity() + 1);
        } else {
            $cartProduct = new CartProduct();
            $cartProduct->setCart($cart);
            $cartProduct->setProduct($product);
            $cartProduct->setQuantity(1);  
            $this->entityManager->persist($cartProduct);
        }

        $this->entityManager->flush();

        return $this->redirectToRoute('home');  //A MODIFIER SI ON VEUT REDIRIGER VERS UNE PAGE BIEN SPECIFIQUE
        
    }

    public function index(): Response
    {
        $cart = $this->cartRepository->findActiveCart();
        if ($cart!=null) {
            $cartProducts = $this->cartProductRepository->findBy(['cart' => $cart]);
        }
        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'cartProducts' => $cartProducts,
        ]);
    }
}
