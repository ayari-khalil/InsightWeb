<?php

namespace App\Entity;

use App\Repository\DiplomeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DiplomeRepository::class)]
class Diplome
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank(message: "La date de début est requise.")]
    private $Date_Debut;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank(message: "La date de fin est requise.")]
    private $Date_Fin;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "La mention est requise.")]
    #[Assert\Length(max: 255, maxMessage: "La mention ne peut pas dépasser {{ limit }} caractères.")]
    #[Assert\Regex(
        pattern: "/^\D+$/",
        message: "La mention ne doit pas contenir de chiffres."
    )]
    private $Mention;

    #[ORM\Column(type: 'string', length: 255)]
    private $email;

    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->Date_Debut;
    }

    public function setDateDebut(\DateTimeInterface $Date_Debut): self
    {
        $this->Date_Debut = $Date_Debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->Date_Fin;
    }

    public function setDateFin(\DateTimeInterface $Date_Fin): self
    {
        $this->Date_Fin = $Date_Fin;

        return $this;
    }

    public function getMention(): ?string
    {
        return $this->Mention;
    }

    public function setMention(string $Mention): self
    {
        $this->Mention = $Mention;

        return $this;
    }
}
