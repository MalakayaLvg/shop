<?php

namespace App\Entity;

use App\Repository\AdressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdressRepository::class)]
class Adress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'adresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\Column]
    private ?int $streetNumber = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $street = null;

    #[ORM\Column]
    private ?int $zipcode = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'billingAddress')]
    private Collection $orderAsBilling;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'deliveryAddress')]
    private Collection $orderAsDelivery;

    public function __construct()
    {
        $this->orderAsBilling = new ArrayCollection();
        $this->orderAsDelivery = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getStreetNumber(): ?int
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(int $streetNumber): static
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getZipcode(): ?int
    {
        return $this->zipcode;
    }

    public function setZipcode(int $zipcode): static
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrderAsBilling(): Collection
    {
        return $this->orderAsBilling;
    }

    public function addOrderAsBilling(Order $orderAsBilling): static
    {
        if (!$this->orderAsBilling->contains($orderAsBilling)) {
            $this->orderAsBilling->add($orderAsBilling);
            $orderAsBilling->setBillingAddress($this);
        }

        return $this;
    }

    public function removeOrderAsBilling(Order $orderAsBilling): static
    {
        if ($this->orderAsBilling->removeElement($orderAsBilling)) {
            // set the owning side to null (unless already changed)
            if ($orderAsBilling->getBillingAddress() === $this) {
                $orderAsBilling->setBillingAddress(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrderAsDelivery(): Collection
    {
        return $this->orderAsDelivery;
    }

    public function addOrderAsDelivery(Order $orderAsDelivery): static
    {
        if (!$this->orderAsDelivery->contains($orderAsDelivery)) {
            $this->orderAsDelivery->add($orderAsDelivery);
            $orderAsDelivery->setDeliveryAddress($this);
        }

        return $this;
    }

    public function removeOrderAsDelivery(Order $orderAsDelivery): static
    {
        if ($this->orderAsDelivery->removeElement($orderAsDelivery)) {
            // set the owning side to null (unless already changed)
            if ($orderAsDelivery->getDeliveryAddress() === $this) {
                $orderAsDelivery->setDeliveryAddress(null);
            }
        }

        return $this;
    }
}
