<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "Le nom ne peut pas être vide")]
    #[Assert\Regex(
        pattern: "/^\D+$/",
        message: "Le nom ne doit pas contenir de chiffres"
    )]
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[Assert\NotBlank(message: "Le domaine ne peut pas être vide")]
    #[Assert\Regex(
        pattern: "/^\D+$/",
        message: "Le domaine ne doit pas contenir de chiffres"
    )]
    #[ORM\Column(length: 255)]
    private ?string $domaine = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\OneToMany(targetEntity: Cours::class, mappedBy: 'formation')]
    private Collection $cours;

    #[ORM\Column(nullable: true)]
    private ?int $nb_Cours = null;

    public function __construct()
    {
        $this->cours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDomaine(): ?string
    {
        return $this->domaine;
    }

    public function setDomaine(string $domaine): static
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * @return Collection<int, Cours>
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(Cours $cour): static
    {
        if (!$this->cours->contains($cour)) {
            $this->cours->add($cour);
            $cour->setFormation($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): static
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getFormation() === $this) {
                $cour->setFormation(null);
            }
        }

        return $this;
    }

    public function getNbCours(): ?int
    {
        return $this->nb_Cours;
    }

    public function setNbCours(?int $nb_Cours): static
    {
        $this->nb_Cours = $nb_Cours;

        return $this;
    }
    public function __toString()
    {
        return $this->nom; // ou une autre propriété que vous souhaitez afficher comme une chaîne
    }
}
