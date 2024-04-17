<?php

namespace App\Entity;

use App\Repository\QuizRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizRepository::class)]
class Quiz
{
   

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $qid = null;

    #[ORM\Column(length: 255)]
    private ?string $question = null;

    #[ORM\Column(length: 255)]
    private ?string $opt1 = null;

    #[ORM\Column(length: 255)]
    private ?string $opt2 = null;

    #[ORM\Column(length: 255)]
    private ?string $opt3 = null;

    #[ORM\Column(length: 255)]
    private ?string $opt4 = null;

    #[ORM\Column(length: 255)]
    private ?string $correct_option = null;


    public function getQid(): ?int
    {
        return $this->qid;
    }

    public function setQid(int $qid): static
    {
        $this->qid = $qid;

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getOpt1(): ?string
    {
        return $this->opt1;
    }

    public function setOpt1(string $opt1): static
    {
        $this->opt1 = $opt1;

        return $this;
    }

    public function getOpt2(): ?string
    {
        return $this->opt2;
    }

    public function setOpt2(string $opt2): static
    {
        $this->opt2 = $opt2;

        return $this;
    }

    public function getOpt3(): ?string
    {
        return $this->opt3;
    }

    public function setOpt3(string $opt3): static
    {
        $this->opt3 = $opt3;

        return $this;
    }

    public function getOpt4(): ?string
    {
        return $this->opt4;
    }

    public function setOpt4(string $opt4): static
    {
        $this->opt4 = $opt4;

        return $this;
    }

    public function getCorrectOption(): ?string
    {
        return $this->correct_option;
    }

    public function setCorrectOption(string $correct_option): static
    {
        $this->correct_option = $correct_option;

        return $this;
    }
}
