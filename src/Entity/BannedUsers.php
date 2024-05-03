<?php

namespace App\Entity;

use App\Repository\BannedUsersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BannedUsersRepository::class)]
class BannedUsers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $studentId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudentId(): ?int
    {
        return $this->studentId;
    }

    public function setStudentId(int $studentId): static
    {
        $this->studentId = $studentId;

        return $this;
    }
}
