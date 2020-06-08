<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AnswerRepository::class)
 */
class Answer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"survey_show"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"survey_show"})
     */
    private $sentence;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"survey_show"})
     */
    private $countAnswer;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="answers")
     */
    private $question;

    public function getSentence(): ?string
    {
        return $this->sentence;
    }

    public function setSentence(string $sentence): self
    {
        $this->sentence = $sentence;

        return $this;
    }

    public function getCountAnswer(): ?int
    {
        return $this->countAnswer;
    }

    public function setCountAnswer(int $countAnswer): self
    {
        $this->countAnswer = $countAnswer;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }


}
