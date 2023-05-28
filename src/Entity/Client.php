<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $nbrPersonne = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateArr = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDep = null;
/*
    #[ORM\Column(length: 255)]
    private ?string $image = null;
*/
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ChambreHote $ChambreHote = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNbrPersonne(): ?int
    {
        return $this->nbrPersonne;
    }

    public function setNbrPersonne(int $nbrPersonne): self
    {
        $this->nbrPersonne = $nbrPersonne;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDateArr(): ?\DateTimeInterface
    {
        return $this->dateArr;
    }

    public function setDateArr(\DateTimeInterface $dateArr): self
    {
        $this->dateArr = $dateArr;

        return $this;
    }

    public function getDateDep(): ?\DateTimeInterface
    {
        return $this->dateDep;
    }

    public function setDateDep(\DateTimeInterface $dateDep): self
    {
        $this->dateDep = $dateDep;

        return $this;
    }
/*
    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image):self
    {
        $this->image = $image;

        return $this;
    }
*/
    public function getChambreHote(): ?ChambreHote
    {
        return $this->ChambreHote;
    }

    public function setChambreHote(?ChambreHote $ChambreHote): self
    {
        $this->ChambreHote = $ChambreHote;

        return $this;
    }
}
