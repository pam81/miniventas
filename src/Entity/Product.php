<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product extends AbstractEntity
{
     const TYPE_WINE = 1;
     const TYPE_FOOD = 2;

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
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     */
    private $price;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $priceBefore;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $type;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country", inversedBy="products")
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Region", inversedBy="products")
     */
    private $region;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="products")
     */
    private $company;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pending;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $available;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $reserved;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BuyProduct", mappedBy="product", orphanRemoval=true)
     */
    private $buyProducts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Issue", mappedBy="product", orphanRemoval=true)
     */
    private $issues;

    public function __construct()
    {
        $this->buyProducts = new ArrayCollection();
        $this->issues = new ArrayCollection();
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPriceBefore(): ?float
    {
        return $this->priceBefore;
    }

    public function setPriceBefore(?float $priceBefore): self
    {
        $this->priceBefore = $priceBefore;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getType(): ?Integer
    {
        return $this->type;
    }

    public function setType(?Integer $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getPending(): ?int
    {
        return $this->pending;
    }

    public function setPending(?int $pending): self
    {
        $this->pending = $pending;

        return $this;
    }

    public function getAvailable(): ?int
    {
        return $this->available;
    }

    public function setAvailable(?int $available): self
    {
        $this->available = $available;

        return $this;
    }

    public function getReserved(): ?int
    {
        return $this->reserved;
    }

    public function setReserved(?int $reserved): self
    {
        $this->reserved = $reserved;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

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
            $buyProduct->setProduct($this);
        }

        return $this;
    }

    public function removeBuyProduct(BuyProduct $buyProduct): self
    {
        if ($this->buyProducts->contains($buyProduct)) {
            $this->buyProducts->removeElement($buyProduct);
            // set the owning side to null (unless already changed)
            if ($buyProduct->getProduct() === $this) {
                $buyProduct->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Issue[]
     */
    public function getIssues(): Collection
    {
        return $this->issues;
    }

    public function addIssue(Issue $issue): self
    {
        if (!$this->issues->contains($issue)) {
            $this->issues[] = $issue;
            $issue->setProduct($this);
        }

        return $this;
    }

    public function removeIssue(Issue $issue): self
    {
        if ($this->issues->contains($issue)) {
            $this->issues->removeElement($issue);
            // set the owning side to null (unless already changed)
            if ($issue->getProduct() === $this) {
                $issue->setProduct(null);
            }
        }

        return $this;
    }
}
