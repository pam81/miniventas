<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WineRepository")
 */
class Wine extends Product
{

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $year;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $oenologist;

    /**
     * @ORM\Column(type="integer")
     */
    private $aged;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Grape", inversedBy="product")
     */
    private $grape;
   

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getOenologist(): ?string
    {
        return $this->oenologist;
    }

    public function setOenologist(?string $oenologist): self
    {
        $this->oenologist = $oenologist;

        return $this;
    }

    public function getAged(): ?int
    {
        return $this->aged;
    }

    public function setAged(int $aged): self
    {
        $this->aged = $aged;

        return $this;
    }

    public function getGrape(): ?Grape
    {
        return $this->grape;
    }

    public function setGrape(?Grape $grape): self
    {
        $this->grape = $grape;

        return $this;
    }

    
}
