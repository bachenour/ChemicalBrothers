<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalesOrderRepository::class)]
#[ORM\Table(name: '`order`')]
class SalesOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateOrder = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateDelivery = null;

    #[ORM\OneToMany(mappedBy: 'salesOrder', targetEntity: OrderAddress::class)]
    private Collection $orderAddresses;

    #[ORM\OneToMany(mappedBy: 'salesOrder', targetEntity: OrderProduct::class)]
    private Collection $orderProducts;

    public function __construct()
    {
        $this->orderAddresses = new ArrayCollection();
        $this->orderProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateOrder(): ?\DateTimeInterface
    {
        return $this->dateOrder;
    }

    public function setDateOrder(\DateTimeInterface $dateOrder): static
    {
        $this->dateOrder = $dateOrder;

        return $this;
    }

    public function getDateDelivery(): ?\DateTimeInterface
    {
        return $this->dateDelivery;
    }

    public function setDateDelivery(?\DateTimeInterface $dateDelivery): static
    {
        $this->dateDelivery = $dateDelivery;

        return $this;
    }

    /**
     * @return Collection<int, OrderAddress>
     */
    public function getOrderAddresses(): Collection
    {
        return $this->orderAddresses;
    }

    public function addOrderAddress(OrderAddress $orderAddress): static
    {
        if (!$this->orderAddresses->contains($orderAddress)) {
            $this->orderAddresses->add($orderAddress);
            $orderAddress->setSalesOrder($this);
        }

        return $this;
    }

    public function removeOrderAddress(OrderAddress $orderAddress): static
    {
        if ($this->orderAddresses->removeElement($orderAddress)) {
            // set the owning side to null (unless already changed)
            if ($orderAddress->getSalesOrder() === $this) {
                $orderAddress->setSalesOrder(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OrderProduct>
     */
    public function getOrderProducts(): Collection
    {
        return $this->orderProducts;
    }

    public function addOrderProduct(OrderProduct $orderProduct): static
    {
        if (!$this->orderProducts->contains($orderProduct)) {
            $this->orderProducts->add($orderProduct);
            $orderProduct->setSalesOrder($this);
        }

        return $this;
    }

    public function removeOrderProduct(OrderProduct $orderProduct): static
    {
        if ($this->orderProducts->removeElement($orderProduct)) {
            // set the owning side to null (unless already changed)
            if ($orderProduct->getSalesOrder() === $this) {
                $orderProduct->setSalesOrder(null);
            }
        }

        return $this;
    }
}
