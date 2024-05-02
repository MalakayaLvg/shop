<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Entity\PaymentMethod;
use App\Form\AdressType;
use App\Form\PaymentMethodType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        $newAdress = new Adress();
        $newPaymentMethod = new PaymentMethod();
        $formAdress = $this->createForm(AdressType::class,$newAdress);
        $formPayment = $this->createForm(PaymentMethodType::class,$newPaymentMethod);

        return $this->render('profile/index.html.twig', [
            "formPayment" => $formPayment,
            "formAdress" => $formAdress
        ]);
    }

    #[Route('/profile/add/payment', name: 'app_profile_add_payment', methods: "POST" ,priority: 5,)]
    public function addPayment(Request $request, EntityManagerInterface $manager)
    {

        $newPayment = new PaymentMethod();
        $formPayment = $this->createForm(PaymentMethodType::class,$newPayment);
        $formPayment->handleRequest($request);
        if ($formPayment->isSubmitted() && $formPayment->isValid())
        {
            $newPayment->setOwner($this->getUser());
            $manager->persist($newPayment);
            $manager->flush();
        }

        return $this->redirectToRoute("app_profile");

    }

    #[Route('/profile/add/adress', name: 'app_profile_add_adress', methods: "POST",priority: 5)]
    public function addAddress(Request $request, EntityManagerInterface $entityManager): Response
    {
        $newAdress = new Adress();
        $formAdress = $this->createForm(AdressType::class, $newAdress);
        $formAdress->handleRequest($request);
        if ($formAdress->isSubmitted() && $formAdress->isValid()) {
            $newAdress->setOwner($this->getUser());
            $entityManager->persist($newAdress);
            $entityManager->flush();

        }
        return $this->redirectToRoute('app_profile');

    }
}
