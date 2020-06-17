<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AnswerRepository::class)
 */
class Answer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"question_list", "question_show", "answer_list"})
     */
    private $id;

    /**
     * @Assert\Type("string")
     * @Assert\Length(min="3", minMessage="Le phrase doit avoir au moins {{ limit }} caractères.")
     * @ORM\Column(type="string", length=255)
     * @Groups({"question_list", "question_show", "answer_list"})
     */
    private $sentence;

    /**
     * @Assert\Type("integer")
     * @ORM\Column(type="integer")
     * @Groups({"question_list", "question_show", "answer_list"})
     */
    private $countAnswer;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="answers")
     */
    private $question;

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
