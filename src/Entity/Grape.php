<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\GrapeRepository")
 */
class Grape extends AbstractEntity
{
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(
     *   min = 1,
     *   max = 180,
     *   minMessage = "The minimum length of name field must be 1 character",
     *   maxMessage = "The maximum length of name field must be 255 character"
     * )
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Wine", mappedBy="type")
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Wine[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Wine $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setGrape($this);
        }

        return $this;
    }

    public function removeProduct(Wine $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getGrape() === $this) {
                $product->setGrape(null);
            }
        }

        return $this;
    }
}
