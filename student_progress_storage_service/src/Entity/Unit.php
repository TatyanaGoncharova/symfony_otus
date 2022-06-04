<?php
declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="`unit`",
 *     indexes={
 *         @ORM\Index(name="unit__task_id__ind", columns={"task_id"}),
 *         @ORM\Index(name="unit__skill_id__ind", columns={"skill_id"})
 *     }))
 * @ORM\Entity
 */
class Unit
{
    /**
     * @ORM\Column(name="id", type="bigint", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private ?int $id = null;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private int $percent;

    /**
     * @ORM\ManyToOne(targetEntity="Task")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="task_id", referencedColumnName="id")
     * })
     */
    private Task $task;

    /**
     * @ORM\ManyToOne(targetEntity="Skill")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="skill_id", referencedColumnName="id")
     * })
     */
    private Skill $skill;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getPercent(): int
    {
        return $this->percent;
    }

    public function setPercent(int $percent): void
    {
        $this->percent = $percent;
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function setTask(Task $task): void
    {
        $this->task = $task;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'task' => $this->task->getTitle(),
            'skill' => $this->skill->getSkillName(),
            'percent' => $this->percent,
        ];
    }
}