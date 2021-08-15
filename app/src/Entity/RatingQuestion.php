<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RatingQuestionRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RatingQuestionRepository::class)
 */
class RatingQuestion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", nullable=false)
     * @Serializer\Groups({"list"})
     */
    private ?string $id;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Serializer\Groups({"list"})
     * @Assert\NotBlank
     * @Assert\Type("integer")
     * @Assert\Range(max=5)
     */
    private int $score;

    /**
     * @ORM\ManyToOne(targetEntity=Rating::class, inversedBy="ratingQuestions", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"question_list"})
     * @Assert\NotBlank
     */
    private ?Rating $rating;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="ratingQuestions", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"rating_list"})
     * @Assert\NotBlank
     */
    private Question $question;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getRating(): ?Rating
    {
        return $this->rating;
    }

    public function setRating(?Rating $rating): self
    {
        if ($rating) {
            $this->rating = $rating;
            $rating->addRatingQuestion($this);
        }

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
