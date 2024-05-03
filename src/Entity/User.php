<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\MappedSuperclass]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'email ne peut pas être vide")]
    #[Assert\Email(message: "L'email doit être une adresse email valide")]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le mot de passe ne peut pas être vide")]
    #[Assert\Length(min: 6, minMessage: "Le mot de passe doit comporter au moins 6 caractères")]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le prénom ne peut pas être vide")]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]{3,}$/',
        message: "Le prénom doit contenir au moins 3 caractères alphabétiques"
    )]
    private ?string $firstName = null;
    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide")]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]{3,}$/',
        message: "Le nom doit contenir au moins 3 caractères alphabétiques"
    )]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "La date de naissance ne peut pas être vide")]
    #[Assert\LessThanOrEqual("today", message: "La date de naissance ne peut pas être dans le futur")]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le numéro de téléphone ne peut pas être vide")]
    #[Assert\Regex(
        pattern: "/^\d{8}$/",
        message: "Le numéro de téléphone doit comporter exactement 8 chiffres"
    )]
    private ?string $phoneNumber = null;
 #[ORM\Column]
#[Assert\NotBlank(message: "Le CIN ne peut pas être vide")]
#[Assert\Regex(
    pattern: "/^\d{8}$/",
    message: "Le CIN doit comporter exactement 8 chiffres"
)]
private ?string $cin = null;

    #[ORM\Column(length: 255)]
    private ?string $role = null;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(int $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(int $cin): static
    {
        $this->cin = $cin;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }
}
