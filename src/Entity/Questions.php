<?php

namespace App\Entity;

use App\Repository\QuestionsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionsRepository::class)]
class Questions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $questionId;

    #[ORM\ManyToOne(targetEntity: Tests::class, inversedBy: 'questions')]
    #[ORM\JoinColumn(name: 'test_id', referencedColumnName: 'test_id', nullable: false)]
    private ?Tests $test;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $questionText;

    public function getTest(): ?Tests
    {
        return $this->test;
    }

    public function setTest(?Tests $test): self
    {
        $this->test = $test;
        return $this;
    }

    public function getQuestionId(): ?int
    {
        return $this->questionId;
    }

    public function setQuestionId(int $questionId): self
    {
        $this->questionId = $questionId;
        return $this;
    }

    public function getQuestionText(): ?string
    {
        return $this->questionText;
    }

    public function setQuestionText(?string $questionText): self
    {
        $this->questionText = $questionText;
        return $this;
    }
}
