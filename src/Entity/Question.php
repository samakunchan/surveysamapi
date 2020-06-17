<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 */
class Question
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"survey_list", "survey_show", "question_list", "question_show"})
     */
    private $id;

    /**
     * @Assert\Type("string")
     * @Assert\Length(min="3", minMessage="Le phrase doit avoir au moins {{ limit }} caractères.")
     * @ORM\Column(type="string", length=255)
     * @Groups({"survey_list", "question_list", "question_show"})
     */
    private $sentence;

    /**
     * @Assert\Type("string")
     * @Assert\Choice({"complete", "pending"}, message="Veuillez choisir parmis les valeurs autorisés: {{ choices }}")
     * @ORM\Column(type="string", length=255)
     * @Groups({"survey_list", "survey_show", "question_list", "question_show"})
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Survey::class, inversedBy="questions")
     */
    private $survey;

    /**
     * @ORM\OneToMany(targetEntity=Answer::class, mappedBy="question", cascade={"persist"})
     * @Groups({"question_list", "question_show", "answer_list"})
     */
    private $answers;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSentence(): ?string
    {
        return $this->sentence;
    }

    public function setSentence(string $sentence): self
    {
        $this->sentence = $sentence;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getSurvey(): ?Survey
    {
        return $this->survey;
    }

    public function setSurvey(?Survey $survey): self
    {
        $this->survey = $survey;

        return $this;
    }

    /**
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->contains($answer)) {
            $this->answers->removeElement($answer);
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

        return $this;
    }
}
