<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            "categories" => $categoryRepository->findAll(),
        ]);
    }

    #[Route('admin/category/create', name: 'create_category')]
    public function create(Request $request,EntityManagerInterface $manager): Response
    {
        $category = new Category();
        $formCategory = $this->createForm(CategoryType::class, $category);
        $formCategory->handleRequest($request);
        if ($formCategory->isSubmitted() && $formCategory->isValid()) {
            $manager->persist($category);
            $manager->flush();

            return $this->redirectToRoute('app_category');
        }



        return $this->render('category/create.html.twig',[
            'formCategory' => $formCategory->createView(),
        ]);
    }

    #[Route('/category/delete/{id}', name: 'delete_category')]
    public function delete(Category $category, EntityManagerInterface $manager): Response
    {
        $manager->remove($category);
        $manager->flush();

        return $this->redirectToRoute('app_category');


    }

    #[Route('/category/edit/{id}', name: 'edit_category')]
    public function edit(Request $request, EntityManagerInterface $manager, Category $category):Response
    {
        $form = $this->createForm(CategoryType::class, $category);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($category);
            $manager->flush();

            return $this->redirectToRoute("app_category");
        }



        return $this->render('category/edit.html.twig', [
            "form"=>$form->createView(),
        ]);

    }
}
