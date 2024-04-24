<?php

namespace App\Controller;

use App\Entity\Product;
use App\Services\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cartService->getCart(),
        ]);
    }

    #[Route('/cart/index/add/{id}/{quantity}', name: 'app_cart_index_add')]
    #[Route('/cart/add/{id}/{quantity}', name: 'app_cart_add')]
    public function addToCart(CartService $cartService, Request $request, Product $product, $quantity): Response
    {
        $cartService->addProduct($product, $quantity);

        $originRoute = $request->attributes->get('_route');
        $redirection = 'app_product_user';
        if ($originRoute == 'app_cart_add'){
            $redirection = 'app_cart';
        }

        return $this->redirectToRoute($redirection);
    }

    #[Route('/cart/remove/one/{id}/{quantity}', name: 'app_cart_remove_one')]
    public function removeOneProduct(CartService $cartService, Product $product, $quantity) : Response
    {
        $cartService->removeOneProduct($product, $quantity);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/row/{id}', name: 'app_cart_remove_row')]
    public function removeOneRow(CartService $cartService, Product $product)
    {
        $cartService->removeOneRow($product);

        return $this->redirectToRoute('app_cart');
    }
}
