<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $orderNumber;

    /**
     * @ORM\Column(type="date")
     */
    private $orderDate;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity=Adresse::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $adresse;

    /**
     * @ORM\ManyToOne(targetEntity=State::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity=Payment::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $payment;

    /**
     * @ORM\OneToMany(targetEntity=OrderLog::class, mappedBy="orderNumber")
     */
    private $orderLogs;

    public function __construct()
    {
        $this->orderLogs = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderNumber(): ?string
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(string $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(\DateTimeInterface $orderDate): self
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getAdresse(): ?Adresse
    {
        return $this->adresse;
    }

    public function setAdresse(?Adresse $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * @return Collection|OrderLog[]
     */
    public function getOrderLogs(): Collection
    {
        return $this->orderLogs;
    }

    public function addOrderLog(OrderLog $orderLog): self
    {
        if (!$this->orderLogs->contains($orderLog)) {
            $this->orderLogs[] = $orderLog;
            $orderLog->setOrderNumber($this);
        }

        return $this;
    }

    public function removeOrderLog(OrderLog $orderLog): self
    {
        if ($this->orderLogs->removeElement($orderLog)) {
            // set the owning side to null (unless already changed)
            if ($orderLog->getOrderNumber() === $this) {
                $orderLog->setOrderNumber(null);
            }
        }

        return $this;
    }


}
