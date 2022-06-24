<?php

namespace App\DTO;

class ProgressAMQPDTO
{
    private array $payload;

    public function __construct(int $userId, int $taskId, int $rate)
    {
        $this->payload = ['userId' => $userId, 'taskId' => $taskId, 'rate' => $rate];
    }

    public function toAMQPMessage(): string
    {
        return json_encode($this->payload);
    }
}
