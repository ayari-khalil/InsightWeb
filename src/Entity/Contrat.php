<?php

namespace App\Entity;

use App\Repository\ContratRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContratRepository::class)]
class Contrat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dateContrat = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $nbDays = null;

    #[ORM\ManyToOne(targetEntity: Ecole::class)]
    #[ORM\JoinColumn(name: 'ecole_id', referencedColumnName: 'id')]
    private ?Ecole $ecole = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    // Other getters and setters...

    public function getDateContrat(): ?\DateTimeInterface
    {
        return $this->dateContrat;
    }

    // Setter method for date_contrat property
    public function setDateContrat(?\DateTimeInterface $dateContrat): self
    {
        $this->dateContrat = $dateContrat;

        return $this;
    }

    // Getter method for nb_days property
    public function getNbDays(): ?int
    {
        return $this->nbDays;
    }

    // Setter method for nb_days property
    public function setNbDays(?int $nbDays): self
    {
        $this->nbDays = $nbDays;

        return $this;
    }

    // Getter method for ecole property
    public function getEcole(): ?Ecole
    {
        return $this->ecole;
    }

    // Setter method for ecole property
    public function setEcole(?Ecole $ecole): self
    {
        $this->ecole = $ecole;

        return $this;
    }
}
