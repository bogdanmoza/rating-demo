<?php

declare(strict_types=1);

namespace App\Entity;

use App\Model\AbstractEntityRouterLoader;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * Client
 * @ORM\Table(name="client", uniqueConstraints={
 *     @ORM\UniqueConstraint(
 *         name="UNIQ_USERNAME",
 *         columns={"username"})
 *     },
 *     indexes={@ORM\Index(name="username_idx", columns={"username"})}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 * @UniqueEntity(
 *     fields={"username"},
 *     message="The username already exists!"
 * )
 */
class Client extends AbstractEntityRouterLoader implements UserInterface, PasswordAuthenticatedUserInterface
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
     * @ORM\Column(name="username", type="string", length=128, nullable=false, options={"comment"="Email as the username"})
     *
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\Email
     *
     * @Serializer\Groups({"list"})
     */
    private ?string $username;

    /**
     * @ORM\Column(type="json")
     *
     * @Serializer\Exclude
     */
    private array $roles = ['ROLE_CLIENT'];

    /**
     * @ORM\Column(name="password", type="string", length=96, nullable=false, options={"comment"="Use password hash with BCRYPT"})
     *
     * @Serializer\Exclude
     */
    private string $password;

    /**
     * @ORM\Column(name="created", type="datetime", nullable=false)
     * @Gedmo\Timestampable
     *
     * @Serializer\Groups({"list"})
     */
    private \DateTime $created;

    /**
     * @ORM\Column(name="first_name", type="string", length=96, nullable=false)
     *
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(max=96)
     *
     * @Serializer\Groups({"list"})
     */
    private ?string $firstName;

    /**
     * @ORM\Column(name="last_name", type="string", length=96, nullable=false)
     *
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     *
     * @Serializer\Groups({"list"})
     */
    private ?string $lastName;

    /**
     * @Assert\NotBlank
    */
    private ?string $plainPassword;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUserIdentifier(): ?string
    {
        return (string) $this->username;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }
}
