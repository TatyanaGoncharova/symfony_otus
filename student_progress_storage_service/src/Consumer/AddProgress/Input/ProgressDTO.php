<?php

namespace App\Consumer\AddProgress\Input;

use Symfony\Component\Validator\Constraints as Assert;

final class ProgressDTO
{
    /**
     * @Assert\Type("numeric")
     */
    private int $userId;

    /**
     * @Assert\Type("numeric")
     */
    private int $taskId;

    /**
     * @Assert\Type("numeric")
     */
    private int $rate;

    public static function createFromQueue(string $messageBody): self
    {
        $message = json_decode($messageBody, true, 512, JSON_THROW_ON_ERROR);
        $result = new self();
        $result->userId = $message['userId'];
        $result->taskId = $message['taskId'];
        $result->rate = $message['rate'];

        return $result;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getTaskId(): int
    {
        return $this->taskId;
    }

    public function getRate(): int
    {
        return $this->rate;
    }
}
