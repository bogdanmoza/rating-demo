<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as Serializer;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=MemberRepository::class)
 * @UniqueEntity(
 *     fields={"username"},
 *     message="The username already exists!"
 * )
 */
class Member implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", nullable=false)
     * @Serializer\Groups({"list"})
     */
    private ?string $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true, nullable=false)
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(max=180)
     * @Serializer\Groups({"list"})
     */
    private string $username;

    /**
     * @ORM\Column(type="json")
     * @Serializer\Groups({"list"})
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Serializer\Exclude
     */
    private string $password;

    /**
     * @ORM\OneToMany(targetEntity=Project::class, mappedBy="member")
     */
    private $projects;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable
     * @Serializer\Groups({"list"})
     */
    private $created;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Project[]
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->setMember($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getMember() === $this) {
                $project->setMember(null);
            }
        }

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }
}
