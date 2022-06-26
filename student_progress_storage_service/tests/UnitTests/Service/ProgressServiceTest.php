<?php

namespace UnitTests\Service;

use App\Entity\Progress;
use App\Entity\Task;
use App\Entity\User;
use App\Service\ProgressService;
use App\Service\UserService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class ProgressServiceTest extends TestCase
{
    /** @var EntityManagerInterface|MockInterface */
    private static $entityManager;
    private const CORRECT_STUDENT = 1;
    private const CORRECT_TASK = 2;
    private const INCORRECT_STUDENT = 3;
    private const INCORRECT_TASK = 4;
    private const RATE = 40;
    private static TagAwareCacheInterface|Mockery\LegacyMockInterface|MockInterface $cache;

    public static function setUpBeforeClass(): void
    {
        /** @var MockInterface|EntityRepository $repository */
        $repository = Mockery::mock(EntityRepository::class);
        $repository->shouldReceive('find')->with(self::CORRECT_STUDENT)->andReturn(new User());
        $repository->shouldReceive('find')->with(self::INCORRECT_STUDENT)->andReturn(null);
        $repository->shouldReceive('find')->with(self::CORRECT_TASK)->andReturn(new Task());
        $repository->shouldReceive('find')->with(self::INCORRECT_TASK)->andReturn(null);
        /** @var MockInterface|EntityManagerInterface $repository */
        self::$entityManager = Mockery::mock(EntityManagerInterface::class);
        self::$entityManager->shouldReceive('getRepository')->with(User::class)->andReturn($repository);
        self::$entityManager->shouldReceive('getRepository')->with(Task::class)->andReturn($repository);
        self::$entityManager->shouldReceive('getRepository')->with(Progress::class)->andReturn($repository);
        self::$entityManager->shouldReceive('persist');
        self::$entityManager->shouldReceive('flush');
        self::$cache = Mockery::mock(TagAwareCacheInterface::class);
        self::$cache->shouldReceive('invalidateTags');
    }

    public function progressDataProvider(): array
    {
        return [
            'both correct' => [self::CORRECT_STUDENT, self::CORRECT_TASK, self::RATE, true],
            'student incorrect' => [self::INCORRECT_STUDENT, self::CORRECT_TASK, self::RATE, false],
            'task incorrect' => [self::CORRECT_STUDENT, self::INCORRECT_TASK, self::RATE, false],
            'both incorrect' => [self::INCORRECT_STUDENT, self::INCORRECT_TASK, self::RATE, false],
        ];
    }

    /**
     * @dataProvider progressDataProvider
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testProgressReturnsCorrectResult(int $userId, int $taskId, int $rate, bool $expected): void
    {
        $progressService = new ProgressService(self::$entityManager, self::$cache);
        $actual = $progressService->saveProgress($userId, $taskId, $rate);
        static::assertSame($expected, $actual, 'Progress should return correct result');
    }
}
