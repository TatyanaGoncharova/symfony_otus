<?php
declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="`user`")
 * @ORM\Entity(repositoryClass=App\Repository\UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Column(name="id", type="bigint", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private ?int $id = null;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, nullable=false, unique=true)
     */
    private string $login;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private DateTime $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private DateTime $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="Progress", mappedBy="student")
     */
    private Collection $progressStates;

    /**
     * @ORM\Column(type="string", length=120, nullable=false)
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=1024, nullable=false)
     */
    private string $roles = '{}';

    public function __construct()
    {
        $this->progressStates = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreatedAt(): void {
        $this->createdAt = new DateTime();
    }

    public function getUpdatedAt(): DateTime {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setUpdatedAt(): void {
        $this->updatedAt = new DateTime();
    }

    public function getProgressStates(): ArrayCollection
    {
        return $this->progressStates;
    }

    public function addProgressStates(Progress $progressStates): void
    {
        if (!$this->progressStates->contains($progressStates)) {
            $this->progressStates->add($progressStates);
        }
    }

    /**
     * @return string[]
     *
     * @throws JsonException
     */
    public function getRoles(): array
    {
        $roles = json_decode($this->roles, true, 512, JSON_THROW_ON_ERROR);
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    /**
     * @param string[] $roles
     *
     * @throws JsonException
     */
    public function setRoles(array $roles): void
    {
        $this->roles = json_encode($roles, JSON_THROW_ON_ERROR);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'login' => $this->login,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }

    public function getUserIdentifier(): string
    {
        return $this->login;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername(): string
    {
        return $this->login;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}