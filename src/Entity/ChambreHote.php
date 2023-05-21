<?php

namespace App\Entity;

use App\Repository\ChambreHoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChambreHoteRepository::class)]
class ChambreHote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomChambre = null;

    #[ORM\Column]
    private ?int $capacite = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column(length: 255)]
    //private ?string $image = null;
    private ?string $image;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomChambre(): ?string
    {
        return $this->nomChambre;
    }

    public function setNomChambre(string $nomChambre): self
    {
        $this->nomChambre = $nomChambre;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): self
    {
        $this->capacite = $capacite;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

/** 
 * @return mixed 
 */ 
public function getImage() 
{ 
    return $this->image;
 } 
/** 
* @param mixed $image 
*/ 
public function setImage($image): void 
{ 
    $this->image = $image;
    return;
 }


    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }
}
