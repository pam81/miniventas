<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BuyProductRepository")
 */
class BuyProduct extends AbstractEntity
{
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="buyProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Buy", inversedBy="buyProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $buy;

    /**
     * @ORM\Column(type="float")
     */
    private $unit_cost;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $send_unit_cost;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getBuy(): ?Buy
    {
        return $this->buy;
    }

    public function setBuy(?Buy $buy): self
    {
        $this->buy = $buy;

        return $this;
    }

    public function getUnitCost(): ?float
    {
        return $this->unit_cost;
    }

    public function setUnitCost(float $unit_cost): self
    {
        $this->unit_cost = $unit_cost;

        return $this;
    }

    public function getSendUnitCost(): ?float
    {
        return $this->send_unit_cost;
    }

    public function setSendUnitCost(?float $send_unit_cost): self
    {
        $this->send_unit_cost = $send_unit_cost;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
