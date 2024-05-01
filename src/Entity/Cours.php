<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Formation;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Cours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'ref', type: 'integer')]
    private ?int $ref = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(
        pattern: '/\D+/', 
        message: "Le nom ne peut pas contenir que des chiffres"
    )]
    private ?string $nom = null;

    #[ORM\ManyToOne(targetEntity: Formation::class, inversedBy: 'cours')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Formation $formation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $file = null;

    public function getRef(): ?int
    {
        return $this->ref;
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

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): self
    {
        $this->formation = $formation;
        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;
        return $this;
    }
}
