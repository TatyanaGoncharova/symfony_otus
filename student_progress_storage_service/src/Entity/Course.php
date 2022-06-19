<?php
declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="`course`")
 * @ORM\Entity(repositoryClass=App\Repository\CourseRepository::class)
 */
class Course
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
    private string $title;

    /**
     * @ORM\OneToMany(targetEntity="Lesson", mappedBy="course")
     */
    private Collection $lessons;

    public function __construct()
    {
        $this->lessons = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getLessons(): ArrayCollection
    {
        return $this->lessons;
    }

    public function addLesson(Lesson $lesson): void
    {
        if (!$this->lessons->contains($lesson)) {
            $this->lessons->add($lesson);
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'lessons' => array_map(static fn(Lesson $lesson) => $lesson->toArray(), $this->lessons->toArray()),
        ];
    }
}