<?php

namespace App\Consumer\AddProgress;

use App\Consumer\AddProgress\Input\ProgressDTO;
use App\Entity\User;
use App\Service\ProgressService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use JsonException;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class Consumer implements ConsumerInterface
{
    private EntityManagerInterface $entityManager;

    private ValidatorInterface $validator;

    private ProgressService $progressService;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator, ProgressService $progressService)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->progressService = $progressService;
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function execute(AMQPMessage $msg): int
    {
        try {
            $progress = ProgressDTO::createFromQueue($msg->getBody());
            $errors = $this->validator->validate($progress);
            if ($errors->count() > 0) {
                return $this->reject((string)$errors);
            }
        } catch (JsonException $e) {
            return $this->reject($e->getMessage());
        }

        $this->progressService->saveProgress($progress->getUserId(), $progress->getTaskId(), $progress->getRate());
        $this->entityManager->clear();
        $this->entityManager->getConnection()->close();

        return self::MSG_ACK;
    }

    private function reject(string $error): int
    {
        echo "Incorrect message: $error";

        return self::MSG_REJECT;
    }
}
