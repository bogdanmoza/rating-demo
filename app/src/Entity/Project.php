<?php

declare(strict_types=1);

namespace App\Entity;

use App\Model\AbstractEntityRouterLoader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * Project
 *
 * @ORM\Table(name="project", indexes={
 *     @ORM\Index(name="member_idx", columns={"member_id"}),
 *     @ORM\Index(name="vico_idx", columns={"vico_id"}),
 *     @ORM\Index(name="created_idx", columns={"created"})}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 */
class Project extends AbstractEntityRouterLoader
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", nullable=false)
     *
     * @Serializer\Groups({"rating_list", "list"})
     */
    private ?string $id;

    /**
     * @ORM\Column(name="created", type="datetime", nullable=false)
     * @Gedmo\Timestampable
     *
     * @Serializer\Groups({"list"})
     */
    private \DateTime $created;

    /**
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     *
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(max=255)
     *
     * @Serializer\Groups({"list"})
     */
    private ?string $title;

    /**
     * @ORM\ManyToOne(targetEntity="Vico")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="vico_id", referencedColumnName="id")
     * })
     *
     * @Serializer\Groups({"list"})
     */
    private Vico $vico;

    /**
     * @ORM\ManyToOne(targetEntity=Member::class, inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Serializer\Groups({"list"})
     */
    private Member $member;

    public function getId(): ?string
    {
        return $this->id;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getVico(): Vico
    {
        return $this->vico;
    }

    public function setVico(Vico $vico): self
    {
        $this->vico = $vico;

        return $this;
    }

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(?Member $member): self
    {
        $this->member = $member;

        return $this;
    }
}
