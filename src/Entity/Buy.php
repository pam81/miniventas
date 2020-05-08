<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BuyRepository")
 */
class Buy extends AbstractEntity
{
   
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $but_at;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $buy_way;

    /**
     * @ORM\Column(type="integer")
     */
    private $payment_method;

    /**
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $arrived_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $estimated_Arrival_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $follow_code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $send_way;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $send_cost;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Provider", inversedBy="buys")
     */
    private $provider;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BuyProduct", mappedBy="buy", orphanRemoval=true)
     */
    private $buyProducts;

    public function __construct()
    {
        $this->buyProducts = new ArrayCollection();
    }

    public function getButAt(): ?\DateTimeInterface
    {
        return $this->but_at;
    }

    public function setButAt(?\DateTimeInterface $but_at): self
    {
        $this->but_at = $but_at;

        return $this;
    }

    public function getBuyWay(): ?int
    {
        return $this->buy_way;
    }

    public function setBuyWay(?int $buy_way): self
    {
        $this->buy_way = $buy_way;

        return $this;
    }

    public function getPaymentMethod(): ?int
    {
        return $this->payment_method;
    }

    public function setPaymentMethod(int $payment_method): self
    {
        $this->payment_method = $payment_method;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getArrivedAt(): ?\DateTimeInterface
    {
        return $this->arrived_at;
    }

    public function setArrivedAt(?\DateTimeInterface $arrived_at): self
    {
        $this->arrived_at = $arrived_at;

        return $this;
    }

    public function getEstimatedArrivalAt(): ?\DateTimeInterface
    {
        return $this->estimated_Arrival_at;
    }

    public function setEstimatedArrivalAt(?\DateTimeInterface $estimated_Arrival_at): self
    {
        $this->estimated_Arrival_at = $estimated_Arrival_at;

        return $this;
    }

    public function getFollowCode(): ?string
    {
        return $this->follow_code;
    }

    public function setFollowCode(?string $follow_code): self
    {
        $this->follow_code = $follow_code;

        return $this;
    }

    public function getSendWay(): ?string
    {
        return $this->send_way;
    }

    public function setSendWay(?string $send_way): self
    {
        $this->send_way = $send_way;

        return $this;
    }

    public function getSendCost(): ?float
    {
        return $this->send_cost;
    }

    public function setSendCost(?float $send_cost): self
    {
        $this->send_cost = $send_cost;

        return $this;
    }

    public function getProvider(): ?Provider
    {
        return $this->provider;
    }

    public function setProvider(?Provider $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * @return Collection|BuyProduct[]
     */
    public function getBuyProducts(): Collection
    {
        return $this->buyProducts;
    }

    public function addBuyProduct(BuyProduct $buyProduct): self
    {
        if (!$this->buyProducts->contains($buyProduct)) {
            $this->buyProducts[] = $buyProduct;
            $buyProduct->setBuy($this);
        }

        return $this;
    }

    public function removeBuyProduct(BuyProduct $buyProduct): self
    {
        if ($this->buyProducts->contains($buyProduct)) {
            $this->buyProducts->removeElement($buyProduct);
            // set the owning side to null (unless already changed)
            if ($buyProduct->getBuy() === $this) {
                $buyProduct->setBuy(null);
            }
        }

        return $this;
    }
}
