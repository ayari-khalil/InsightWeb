<?php

namespace App\Entity;

use App\Repository\CustomerServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerServiceRepository::class)]
class CustomerService
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idsup = null;

    #[ORM\Column(length: 255)]
    private ?string $fullname = null;

    #[ORM\Column(length: 255)]
    private ?string $emailsup = null;

    #[ORM\Column]
    private ?int $pnsup = null;

    #[ORM\Column(length: 255)]
    private ?string $issue = null;

    #[ORM\Column(length: 255)]
    private ?string $subject = null;

    #[ORM\Column(length: 255)]
    private ?string $message = null;

    #[ORM\Column]
    private ?int $stater = 0;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Responses", mappedBy="customerService")
     */
    private $responses;

    public function __construct()
    {
        $this->responses = new ArrayCollection();
    }

    /**
     * @return Collection|Responses[]
     */
    public function getResponses(): Collection
    {
        return $this->responses;
    }

    public function getIdsup(): ?int
    {
        return $this->idsup;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): static
    {
        $this->fullname = $fullname;

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

    public function getPnsup(): ?int
    {
        return $this->pnsup;
    }

    public function setPnsup(int $pnsup): static
    {
        $this->pnsup = $pnsup; 
    
        return $this;
    }

    public function getIssue(): ?string
    {
        return $this->issue;
    }

    public function setIssue(string $issue): static
    {
        $this->issue = $issue;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getStater(): ?int
    {
        return $this->stater;
    }

    public function setStater(int $stater): static
    {
        $this->stater = $stater;

        return $this;
    }
}
