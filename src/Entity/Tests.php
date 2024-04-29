<?php

namespace App\Entity;

use App\Repository\TestsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestsRepository::class)]
class Tests
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $test_id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $duree;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $note;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $matiere;
/**
 * @ORM\OneToMany(targetEntity=Questions::class, mappedBy="test", cascade={"persist", "remove"}, fetch="EAGER")
 */
private Collection $questions;


    public function __construct()
    {
        $this->test_id = null;
        $this->questions = new ArrayCollection(); 
    }


    public function getTest_id(): ?int
{
    return $this->test_id;
}


    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getMatiere(): ?string
    {
        return $this->matiere;
    }

    public function setMatiere(string $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }
    /**
     * @return Collection|Questions[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Questions $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setTest($this);
        }
        return $this;
    }

    public function removeQuestion(Questions $question): self
    {
        if ($this->questions->removeElement($question)) {
           
            if ($question->getTest() === $this) {
                $question->setTest(null);
            }
        }
        return $this;
    }
}