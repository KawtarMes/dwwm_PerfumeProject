<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseRepository::class)]
class Purchase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'purchase')]
    private ?Perfume $perfume = null;

    #[ORM\ManyToOne(inversedBy: 'purchases')]
    private ?OrderPurchase $orderPurchase = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPerfume(): ?Perfume
    {
        return $this->perfume;
    }

    public function setPerfume(?Perfume $perfume): static
    {
        $this->perfume = $perfume;

        return $this;
    }

    public function getOrderPurchase(): ?OrderPurchase
    {
        return $this->orderPurchase;
    }

    public function setOrderPurchase(?OrderPurchase $orderPurchase): static
    {
        $this->orderPurchase = $orderPurchase;

        return $this;
    }
}
