<?php

namespace App\Entity;

use App\Repository\RentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RentRepository::class)
 */
class Rent
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $bikeNbr;

    /**
     * @ORM\Column(type="integer")
     */
    private $dayNbr;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="rents")
     */
    private $User;

    /**
     * @ORM\Column(type="date")
     */
    private $startDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBikeNbr(): ?int
    {
        return $this->bikeNbr;
    }

    public function setBikeNbr(int $bikeNbr): self
    {
        $this->bikeNbr = $bikeNbr;

        return $this;
    }

    public function getDayNbr(): ?int
    {
        return $this->dayNbr;
    }

    public function setDayNbr(int $dayNbr): self
    {
        $this->dayNbr = $dayNbr;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }
    public function __toString(){
        // to show the name of the Category in the select
        return $this->User->getUsername();
        // to show the id of the Category in the select
        // return $this->id;
    }
}
