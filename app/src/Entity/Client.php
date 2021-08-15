<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * Client
 * @OA\Schema()
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
class Client
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", nullable=false)
     * @OA\Property(description="The unique identifier.")
     * @Serializer\Groups({"list"})
     */
    private ?string $id;

    /**
     * @ORM\Column(name="username", type="string", length=128, nullable=false, options={"comment"="Email as the username"})
     * @OA\Property(type="string", maxLength=128)
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(max="128")
     * @Serializer\Groups({"list"})
     */
    private string $username;

    /**
     * @ORM\Column(name="password", type="string", length=96, nullable=false, options={"comment"="Use password hash with BCRYPT"})
     * @Serializer\Exclude
     */
    private string $password;

    /**
     * @ORM\Column(name="created", type="datetime", nullable=false)
     * @OA\Property(type="datetime")
     * @Gedmo\Timestampable
     * @Serializer\Groups({"list"})
     */
    private \DateTime $created;

    /**
     * @ORM\Column(name="first_name", type="string", length=96, nullable=false)
     * @OA\Property(type="string", maxLength=96)
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(max="96")
     * @Serializer\Groups({"list"})
     */
    private string $firstName;

    /**
     * @ORM\Column(name="last_name", type="string", length=96, nullable=false)
     * @OA\Property(type="string", maxLength=96)
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(max="128")
     * @Serializer\Groups({"list"})
     */
    private string $lastName;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    public function setCreated(\DateTime $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }
}
