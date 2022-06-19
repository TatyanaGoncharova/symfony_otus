<?php
declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="`skill`")
 * @ORM\Entity(repositoryClass=App\Repository\SkillRepository::class)
 */
class Skill
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
     * @ORM\Column(type="string", length=32, nullable=false)
     */
    private string $skillName;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getSkillName(): string
    {
        return $this->skillName;
    }

    public function setSkillName(string $skillName): void
    {
        $this->skillName = $skillName;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'skillName' => $this->skillName,
        ];
    }
}