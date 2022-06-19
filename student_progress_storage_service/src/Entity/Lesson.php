<?php
declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="`lesson`",
 *     indexes={
 *         @ORM\Index(name="lesson__course_id__ind", columns={"course_id"})
 *     })
 * @ORM\Entity(repositoryClass=App\Repository\LessonRepository::class)
 */
class Lesson
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
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="lessons")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     * })
     */
    private Course $course;

    /**
     * @ORM\OneToMany(targetEntity="Task", mappedBy="lesson")
     */
    private Collection $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
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

    public function getCourse(): Course
    {
        return $this->course;
    }

    public function setCourse(Course $course): void
    {
        $this->course = $course;
    }

    public function getTasks(): ArrayCollection
    {
        return $this->tasks;
    }

    public function addTasks(Task $task): void
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'course' => $this->course,
            'tasks' => array_map(static fn(Task $task) => $task->toArray(), $this->tasks->toArray()),
        ];
    }
}