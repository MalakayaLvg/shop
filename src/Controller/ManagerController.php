<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


// USER :
// name : manager, mp: managermdp
#[Route("/manager")]
class ManagerController extends AbstractController
{
    #[Route('/', name: 'app_manager')]
    public function index(OrderRepository $orderRepository): Response
    {


        return $this->render('manager/index.html.twig', [
            "orders"=>$orderRepository->findAll()
        ]);
    }
    #[Route('/change/status/{id}/{status}', name: "app_manager_change_status")]
    public function changeOrderStatus(Order $order, EntityManagerInterface $entityManager,$status): Response
    {
        $order->setStatus($status);

        $entityManager->persist($order);
        $entityManager->flush();

        return $this->redirectToRoute("app_manager");
    }
}
