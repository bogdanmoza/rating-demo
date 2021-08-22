<?php

namespace App\Entity;

use App\Model\AbstractEntityRouterLoader;
use App\Repository\RoleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=RoleRepository::class)
 */
class Role extends AbstractEntityRouterLoader
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
     * @ORM\Column(type="string", length=20, nullable=false)
     *
     * @Assert\NotBlank
     * @Assert\Length(max=20, min=6)
     * @Assert\Type("string")
     *
     * @Serializer\Groups({"list"})
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Length(max=255)
     * @Assert\Type("string")
     *
     * @Serializer\Groups({"list"})
     */
    private ?string $description;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Gedmo\Timestampable
     *
     * @Assert\NotBlank
     *
     * @Serializer\Groups({"list"})
     */
    private \DateTime $created;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
