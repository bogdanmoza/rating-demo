<?php

declare(strict_types=1);

namespace App\Entity;

use App\Model\AbstractEntityRouterLoader;
use App\Repository\RatingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=RatingRepository::class)
 */
class Rating extends AbstractEntityRouterLoader
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", nullable=false)
     *
     * @Serializer\Groups({"list"})
     */
    private ?string $id;

    /**
     * @ORM\Column(type="integer", nullable=false)
     *
     * @Assert\NotBlank
     * @Assert\Type("integer")
     * @Assert\Range(max=5)
     *
     * @Serializer\Groups({"list"})
     */
    private ?int $score;

    /**
     * @ORM\Column(type="string", length=1000, nullable=false)
     *
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(max="1000")
     *
     * @Serializer\Groups({"list"})
     */
    private ?string $comment;

    /**
     * @ORM\OneToMany(targetEntity=RatingQuestion::class, mappedBy="rating", orphanRemoval=true, cascade={"persist"})
     *
     * @Assert\Valid
     *
     * @Serializer\Groups({"list"})
     */
    private $ratingQuestions;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Gedmo\Timestampable
     *
     * @Serializer\Groups({"list"})
     */
    private \DateTime $created;

    /**
     * @ORM\ManyToOne(targetEntity=Vico::class, inversedBy="ratings")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Serializer\Groups({"list"})
     */
    private $vico;

    public function __construct()
    {
        $this->ratingQuestions = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

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
            $ratingQuestion->setRating($this);
        }

        return $this;
    }

    public function removeRatingQuestion(RatingQuestion $ratingQuestion): self
    {
        if ($this->ratingQuestions->removeElement($ratingQuestion) && $ratingQuestion->getRating() === $this) {
            // set the owning side to null (unless already changed)
            $ratingQuestion->setRating(null);
        }

        return $this;
    }

    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    public function setCreated(\DateTime $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getVico(): ?Vico
    {
        return $this->vico;
    }

    public function setVico(?Vico $vico): self
    {
        $this->vico = $vico;

        return $this;
    }
}
