<?php

declare(strict_types=1);

namespace App\Entity;

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
class Vico
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", nullable=false)
     * @Serializer\Groups({"list"})
     */
    private ?string $id;

    /**
     * @ORM\Column(name="name", type="string", length=64, nullable=false)
     * @Serializer\Groups({"list"})
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(min="5", max="64")
     */
    private string $name;

    /**
     * @ORM\Column(name="created", type="datetime", nullable=false)
     * @Gedmo\Timestampable
     * @Serializer\Groups({"list"})
     */
    private \DateTime $created;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName(string $name): self
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
}
