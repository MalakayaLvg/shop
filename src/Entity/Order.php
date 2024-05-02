<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $customer = null;

    #[ORM\ManyToOne(inversedBy: 'orderAsBilling')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adress $billingAddress = null;

    #[ORM\ManyToOne(inversedBy: 'orderAsDelivery')]
    private ?Adress $deliveryAddress = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PaymentMethod $paymentMethod = null;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\Column]
    private ?int $deliveryStatus = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(?User $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getBillingAddress(): ?Adress
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(?Adress $billingAddress): static
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    public function getDeliveryAddress(): ?Adress
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(?Adress $deliveryAddress): static
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    public function getPaymentMethod(): ?PaymentMethod
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?PaymentMethod $paymentMethod): static
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDeliveryStatus(): ?int
    {
        return $this->deliveryStatus;
    }

    public function setDeliveryStatus(int $deliveryStatus): static
    {
        $this->deliveryStatus = $deliveryStatus;

        return $this;
    }
}
