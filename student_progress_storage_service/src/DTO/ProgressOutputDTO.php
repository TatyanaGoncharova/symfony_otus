<?php

namespace App\DTO;

use App\Entity\Progress;
use App\Entity\Task;
use App\Entity\User;
use JsonException;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

class ProgressOutputDTO
{
    /**
     * @Assert\NotBlank()
     */
    public User $student;

    public Task $task;

    public DateTime $createdAt;

    public DateTime $updatedAt;

    public int $rate;

    public function __construct(Progress $progress)
    {
        $this->student = $progress->getStudent();
        $this->task = $progress->getTask();
        $this->createdAt = $progress->getCreatedAt();
        $this->updatedAt = $progress->getUpdatedAt();
        $this->rate = $progress->getRate();
    }
}
