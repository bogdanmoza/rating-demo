<?php

declare(strict_types=1);

namespace App\Entity;

use App\Model\AbstractEntityRouterLoader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Vico
 *
 * @ORM\Table(name="vico", indexes={@ORM\Index(name="name_idx", columns={"name"})})
 * @ORM\Entity(repositoryClass="App\Repository\VicoRepository")
 */
class Vico extends AbstractEntityRouterLoader
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
     * @ORM\Column(name="name", type="string", length=64, nullable=false)
     *
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(min=5, max=64)
     *
     * @Serializer\Groups({"list"})
     */
    private ?string $name;

    /**
     * @ORM\Column(name="created", type="datetime", nullable=false)
     * @Gedmo\Timestampable
     *
     */
    private \DateTime $created;

    /**
     * @ORM\OneToMany(targetEntity=Rating::class, mappedBy="vico", orphanRemoval=true)
     * @Serializer\Exclude
     */
    private $ratings;

    public function __construct()
    {
        $this->ratings = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    public function setCreated(\DateTime $created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return Collection|Rating[]
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings[] = $rating;
            $rating->setVico($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getVico() === $this) {
                $rating->setVico(null);
            }
        }

        return $this;
    }
}
