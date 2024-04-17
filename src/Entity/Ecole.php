<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


use App\Repository\EcoleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EcoleRepository::class)]
class Ecole
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(nullable: true)]
    private ?int $nb_professeur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getNbProfesseur(): ?int
    {
        return $this->nb_professeur;
    }

    public function setNbProfesseur(?int $nb_professeur): static
    {
        $this->nb_professeur = $nb_professeur;

        return $this;
    }
  /**
     * @ORM\OneToMany(targetEntity=Professeur::class, mappedBy="ecole")
     */
    private $professeurs;

    public function __construct()
    {
        $this->professeurs = new ArrayCollection();
    }

    /**
     * @return Collection|Professeur[]
     */
    public function getProfesseurs(): Collection
    {
        return $this->professeurs;
    }

    public function addProfesseur(Professeur $professeur): self
    {
        if (!$this->professeurs->contains($professeur)) {
            $this->professeurs[] = $professeur;
            $professeur->setEcole($this->getId());
        }

        return $this;
    }

    public function removeProfesseur(Professeur $professeur): self
    {
        if ($this->professeurs->removeElement($professeur)) {

            if ($professeur->getEcole() === $this) {
                $professeur->setEcole(null);
            }
        }

        return $this;
    }

    /**
     * @ORM\OneToOne(targetEntity=Contrat::class, mappedBy="ecole", cascade={"persist", "remove"})
     */
    private ?Contrat $contrat = null;

    public function getContrat(): ?Contrat
    {
        return $this->contrat;
    }

    public function setContrat(?Contrat $contrat): self
    {
        $this->contrat = $contrat;

        // set the owning side of the relation if necessary
        if ($contrat !== null && $contrat->getEcole() !== $this) {
            $contrat->setEcole($this);
        }

        return $this;
    }


 
    /**
     * @ORM\OneToOne(targetEntity=Ecole::class, inversedBy="contrat")
     */
    private ?Ecole $ecole = null;

    public function getEcole(): ?Ecole
    {
        return $this->ecole;
    }

    public function setEcole(?Ecole $ecole): self
    {
        $this->ecole = $ecole;

        return $this;
    }
}