<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 */
class Question
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", nullable=false)
     * @Serializer\Groups({"list", "rating_list"})
     */
    private ?string $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(min=5, max=50)
     * @Serializer\Groups({"list"})
     */
    private string $title;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(min=5, max=100)
     * @Serializer\Groups({"list"})
     */
    private string $description;

    /**
     * @ORM\OneToMany(targetEntity=RatingQuestion::class, mappedBy="question", orphanRemoval=true)
     * @Serializer\Exclude
     */
    private $ratingQuestions;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Groups({"list"})
     * @Gedmo\Timestampable
     */
    private \DateTime $created;

    public function __construct()
    {
        $this->ratingQuestions = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|RatingQuestion[]
     */
    public function getRatingQuestions(): Collection
    {
        return $this->ratingQuestions;
    }

    public function addRatingQuestion(RatingQuestion $ratingQuestion): self
    {
        if (!$this->ratingQuestions->contains($ratingQuestion)) {
            $this->ratingQuestions[] = $ratingQuestion;
            $ratingQuestion->setQuestion($this);
        }

        return $this;
    }

    public function removeRatingQuestion(RatingQuestion $ratingQuestion): self
    {
        if ($this->ratingQuestions->removeElement($ratingQuestion)) {
            // set the owning side to null (unless already changed)
            if ($ratingQuestion->getQuestion() === $this) {
                $ratingQuestion->setQuestion(null);
            }
        }

        return $this;
    }

    public function getCreated(): \DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }
}
