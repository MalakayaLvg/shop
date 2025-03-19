<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// USER
// barthelemy , mp : barthelemy

#[Route('/product')]
class ProductUserController extends AbstractController
{
    #[Route('/', name: 'app_product_user')]
    public function index(ProductRepository $productRepository): Response
    {


        return $this->render('product_user/index.html.twig', [
            "products" => $productRepository->findAll(),
        ]);
    }

    #[Route('/show/{id}', name: 'app_product_user_show')]
    public function show(Product $product) : Response
    {
        return $this->render('product_user/show.html.twig', [
            "product" => $product,
        ]);
    }
}
