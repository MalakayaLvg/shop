<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Product;
use App\Form\ImageType;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'app_product', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            "products" => $productRepository->findAll(),
        ]);
    }

    #[Route('/product/{id}', name: 'app_product_show')]
    function show(Product $product, Request $request, EntityManagerInterface $manager): Response
    {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $image->setProduct($product);
            $manager->persist($image);
            $manager->flush();

            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
        }

        return $this->render('product/show.html.twig', [
            "product" => $product,
            "form" => $form->createView(),
        ]);
    }

    #[Route('/create', name: 'app_product_create')]
    public function create(Request $request, EntityManagerInterface $manager) : Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product->setCreatedAt(new \DateTime());
            $manager->persist($product);
            $manager->flush();
            return $this->redirectToRoute('app_product');
        }


        return $this->render('product/create.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    #[ROute('/edit/{id}', name: 'app_product_edit')]
    public function edit(Product $product, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($product);
            $manager->flush();

            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
        }

        return $this->render('product/edit.html.twig',[
            "product" => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_product_delete')]
    public function delete(Product $product, Request $request, EntityManagerInterface $manager): Response
    {
        $manager->remove($product);
        $manager->flush();

        return $this->redirectToRoute('app_product');
    }

    #[Route('/image/delete/{id}', name: 'app_product_image_delete')]
    public function deleteImageProduct(Image $image, EntityManagerInterface $manager) : Response
    {
        $manager->remove($image);
        $manager->flush();

        return $this->redirectToRoute('app_product');
    }




}
