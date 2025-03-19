<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItems;
use App\Repository\AdressRepository;
use App\Repository\OrderRepository;
use App\Repository\PaymentMethodRepository;
use App\Services\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/order')]
class OrderController extends AbstractController
{

    #[Route('/selection', name: 'app_selection', methods: ['GET', 'POST'], priority: 5)]
    public function selection(                          AdressRepository $addressRepository,
                                                        PaymentMethodRepository $paymentMethodRepository,
                                                        EntityManagerInterface $entityManager,
                                                        CartService $cartService,
                                                        Request $request): Response

    {
        $adresses = $this->getUser()->getAdresses();
        $paymentMethods = $this->getUser()->getPaymentMethods();

        $form = $this->createFormBuilder()
            ->add("billing", ChoiceType::class, [
                "choices" => $adresses,
                'choice_label' => "street",
                'choice_value' => "id",
            ])
            ->add("delivery", ChoiceType::class, [
                "choices" => $adresses,
                'choice_label' => "street",
                'choice_value' => "id",
            ])
            ->add("payment", ChoiceType::class, [
                "choices" => $paymentMethods,
                'choice_label' => "cardNumber",
                'choice_value' => "id",
            ])
            ->setMethod('POST')
            ->getForm();

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            if ($formData['billing']=== null || $formData['delivery']===null || $formData['payment']===null )
            {
                return $this->render('order/selection.html.twig', [
                    'controller_name' => 'OrderController',
                    'form' => $form->createView()
                ]);

            }else{

                $order = new Order();
                $order->setCustomer($this->getUser());
                $order->setBillingAddress($formData['billing']);
                $order->setDeliveryAddress($formData['delivery']);
                $order->setPaymentMethod($formData['payment']);
                $order->setStatus(0);
                $order->setDeliveryStatus(0);
                $order->setTotal($cartService->getTotal());

                $entityManager->persist($order);

                foreach ($cartService->getCart() as $cartItems)
                {
                    $orderItems = new OrderItems();
                    $orderItems->setProduct($cartItems["product"]);
                    $orderItems->setQuantity($cartItems["quantity"]);
                    $orderItems->setOfOrder($order);
                    $entityManager->persist($orderItems);
                }

                $entityManager->flush();
                return $this->redirectToRoute('app_recap_order', ['id' => $order->getId()]);

            }

        }

        return $this->render('order/selection.html.twig', [
            'controller_name' => 'OrderController',
            'form' => $form->createView()
        ]);
    }



    #[Route('/recap/{id}', name: 'app_recap_order', methods: ['POST', 'GET'])]
    public function recapOrder($id,OrderRepository $orderRepository): Response
    {
        return $this->render('order/recap.html.twig', [
            'order' => $orderRepository->find($id),
        ]);
    }

    #[Route('/pay/{id}' , name: 'app_pay', methods: 'GET')]
    public function payOrder(Order $order, EntityManagerInterface $entityManager, CartService $cartService):Response
    {
        if($order->getCustomer() ==! $this->getUser()){
            return $this->redirectToRoute("app_home");
        }

        $order->setStatus(1);
        $cartService->emptyCart();

        $entityManager->persist($order);
        $entityManager->flush();

        return $this->render('order/payed.html.twig', [
            'order'=>$order
        ]);
    }
}