<?php

namespace App\Entity;

use App\Repository\ResponsesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: ResponsesRepository::class)]
#[Broadcast]
class Responses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idres = null;

    #[ORM\Column]
    private ?int $idsup = null;

    #[ORM\Column(length: 255)]
    private ?string $emailsup = null;

    #[ORM\Column(length: 255)]
    private ?string $reponse = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dater = null;

    public function getIdres(): ?int
    {
        return $this->idres;
    }

    public function getIdsup(): ?string
    {
        return $this->idsup;
    }

    public function setIdsup(int $idsup): static
    {
        $this->idsup = $idsup;

        return $this;
    }

    public function getEmailsup(): ?string
    {
        return $this->emailsup;
    }

    public function setEmailsup(string $emailsup): static
    {
        $this->emailsup = $emailsup;

        return $this;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): static
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getDater(): ?\DateTimeInterface
    {
        return $this->dater;
    }

    public function setDater(\DateTimeInterface $dater): static
    {
        $this->dater = $dater;

        return $this;
    }
}
