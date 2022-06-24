<?php
declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="`progress`",
 *     indexes={
 *         @ORM\Index(name="progress__user_id__ind", columns={"user_id"}),
 *         @ORM\Index(name="progress__task_id__ind", columns={"task_id"})
 *     })
 * @ORM\Entity(repositoryClass=App\Repository\ProgressRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Progress
{
    /**
     * @ORM\Column(name="id", type="bigint", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private ?int $id = null;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private int $rate;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="progressStates")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private User $student;

    /**
     * @ORM\ManyToOne(targetEntity="Task")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="task_id", referencedColumnName="id")
     * })
     */
    private Task $task;

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


    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getRate(): int
    {
        return $this->rate;
    }

    public function setRate(int $rate): void
    {
        $this->rate = $rate;
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

    public function getTask(): Task
    {
        return $this->task;
    }

    public function setTask(Task $task): void
    {
        $this->task = $task;
    }

    public function getStudent(): User
    {
        return $this->student;
    }

    public function setStudent(User $student): void
    {
        $this->student = $student;
    }

    public function toArray(): array
    {
        $lesson = $this->task->getLesson();
        return [
            'id' => $this->id,
            'student' => $this->student->getLogin(),
            'task' => $this->task->getTitle(),
            'lesson' => $lesson->getTitle(),
            'course' => $lesson->getCourse()->getTitle(),
            'rate' => $this->rate,
        ];
    }
}