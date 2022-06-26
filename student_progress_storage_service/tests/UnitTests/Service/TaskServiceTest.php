<?php

namespace UnitTests\Service;

use App\Entity\Lesson;
use App\Entity\Progress;
use App\Entity\Task;
use App\Entity\User;
use App\Service\ProgressService;
use App\Service\TaskService;
use App\Service\UserService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class TaskServiceTest extends TestCase
{
    /** @var EntityManagerInterface|MockInterface */
    private static $entityManager;
    private const CORRECT_TITLE = "title";
    private const CORRECT_LESSON = 2;
    private const INCORRECT_TITLE = '';
    private const INCORRECT_LESSON = 4;

    public static function setUpBeforeClass(): void
    {
        /** @var MockInterface|EntityRepository $repository */
        $repository = Mockery::mock(EntityRepository::class);
        $repository->shouldReceive('find')->with(self::CORRECT_LESSON)->andReturn(new Lesson());
        $repository->shouldReceive('find')->with(self::INCORRECT_LESSON)->andReturn(null);
        /** @var MockInterface|EntityManagerInterface $repository */
        self::$entityManager = Mockery::mock(EntityManagerInterface::class);
        self::$entityManager->shouldReceive('getRepository')->with(Lesson::class)->andReturn($repository);
        self::$entityManager->shouldReceive('persist');
        self::$entityManager->shouldReceive('flush');
    }

    public function taskDataProvider(): array
    {
        return [
            'both correct' => [self::CORRECT_TITLE, self::CORRECT_LESSON,  true],
            'title incorrect' => [self::INCORRECT_TITLE, self::CORRECT_LESSON,  false],
            'lesson incorrect' => [self::CORRECT_TITLE, self::INCORRECT_LESSON,  true],
            'both incorrect' => [self::INCORRECT_TITLE, self::INCORRECT_LESSON,  false],
        ];
    }

    /**
     * @dataProvider taskDataProvider
     */
    public function testProgressReturnsCorrectResult(string $title, int $lessonId, bool $expected): void
    {
        $taskService = new TaskService(self::$entityManager);
        $actual = $taskService->saveTask($title, $lessonId);
        static::assertSame($expected, $actual, 'Task should return correct result');
    }
}
