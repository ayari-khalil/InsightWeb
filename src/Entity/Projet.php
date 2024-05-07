<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Projet
 *
 * @ORM\Table(name="projet", indexes={
 *     @ORM\Index(name="idSujet_2", columns={"domaine"}),
 *     @ORM\Index(name="idSujet_3", columns={"domaine"}),
 *     @ORM\Index(name="idSujet", columns={"domaine"})
 * })
 * @ORM\Entity
 */
class Projet
{
    /**
     * @var int
     *
     * @ORM\Column(name="idProjet", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idprojet;

    /**
     * @var string
     *
     * @ORM\Column(name="nomProjet", type="string", length=255, nullable=false)
     */
    private $nomprojet;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="nomEntreprise", type="string", length=255, nullable=false)
     */
    private $nomentreprise;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="numTel", type="string", length=20, nullable=false)
     */
    private $numtel;

    /**
     * @var \Sujet|null
     *
     * @ORM\ManyToOne(targetEntity="Sujet")
     * @ORM\JoinColumn(name="domaine", referencedColumnName="idSujet")
     */
    private $domaine;

    // Ajout des getters et setters

    public function getIdprojet(): ?int
    {
        return $this->idprojet;
    }

    public function getNomprojet(): ?string
    {
        return $this->nomprojet;
    }

    public function setNomprojet(string $nomprojet): self
    {
        $this->nomprojet = $nomprojet;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNomentreprise(): ?string
    {
        return $this->nomentreprise;
    }

    public function setNomentreprise(string $nomentreprise): self
    {
        $this->nomentreprise = $nomentreprise;

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

    public function getNumtel(): ?string
    {
        return $this->numtel;
    }

    public function setNumtel(string $numtel): self
    {
        $this->numtel = $numtel;

        return $this;
    }

    public function getDomaine(): ?Sujet
    {
        return $this->domaine;
    }

    public function setDomaine(?Sujet $domaine): self
    {
        $this->domaine = $domaine;

        return $this;
    }
}
