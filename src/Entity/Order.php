<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'integer')]
    private $nb_products;

    #[ORM\Column(type: 'float')]
    private $total_account;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private $user;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $status;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderItems::class, cascade: ["persist"])]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private $items;

    #[ORM\Column(type: 'string', length: 255)]
    private $address;

    #[ORM\Column(type: 'string', length: 255)]
    private $zipcode;

    #[ORM\Column(type: 'string', length: 255)]
    private $city;


    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->status = false;
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getNbProducts(): ?int
    {
        return $this->nb_products;
    }

    public function setNbProducts(int $nb_products): self
    {
        $this->nb_products = $nb_products;

        return $this;
    }

    public function getTotalAccount(): ?float
    {
        return $this->total_account;
    }

    public function setTotalAccount(float $total_account): self
    {
        $this->total_account = $total_account;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection|OrderItems[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }


    public function addItem(OrderItems $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setOrderId($this);
        }

        return $this;
    }

    public function removeItem(OrderItems $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getOrderId() === $this) {
                $item->setOrderId(null);
            }
        }

        return $this;
    }
}
