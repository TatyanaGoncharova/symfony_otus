<?php

namespace UnitTests\Entity;

use App\Entity\Course;
use App\Entity\Lesson;
use App\Entity\Progress;
use App\Entity\Task;
use App\Entity\User;
use DateTime;
use PHPUnit\Framework\TestCase;

class ProgressTest extends TestCase
{
    public function progressDataProvider(): array
    {
        $expectedPositive = [
            'id' => 5,
            'student' => 'Terry Pratchett',
            'task' => 'make tests',
            'lesson' => 'unit tests',
            'course' => 'php',
            'rate' => 40,
        ];

        $expectedNoLesson = [
            'id' => 5,
            'student' => 'Terry Pratchett',
            'task' => 'make something',
            'lesson' => null,
            'course' => null,
            'rate' => 40,
        ];
        return [
            'positive' => [
                $this->makeProgress($expectedPositive),
                $expectedPositive,
            ],
            'no_task' => [
                $this->makeProgress($expectedNoLesson),
                $expectedNoLesson,
            ]
        ];
    }

    /**
     * @dataProvider progressDataProvider
     */
    public function testToArrayReturnsCorrectValues(Progress $progress, array $expected): void
    {
        $actual = $progress->toArray();
        static::assertSame($expected, $actual, 'Progress::toArray should return correct result');
    }

    private function makeProgress($data): ?Progress
    {
        $progress = new Progress();
        $progress->setId($data['id']);
        $user = $this->makeUser($data['student']);
        $progress->setStudent($user);
        $course = $this->makeCourse($data['course']);
        $lesson = $this->makeLesson($data['lesson'], $course);
        $task = $this->makeTask($data['task'], $lesson);
        $progress->setTask($task);
        $progress->setRate($data['rate']);
        return $progress;
    }

    private function makeUser(string $login): User
    {
        $user = new User();
        $user->setLogin($login);
        return $user;
    }

    private function makeTask(?string $taskTitle, ?Lesson $lesson): Task
    {
        $task = new Task();
        if(!empty($taskTitle)){
            $task->setTitle($taskTitle);
        }
        $task->setLesson($lesson);
        return $task;
    }

    private function makeLesson(?string $lessonTitle, ?Course $course): ?Lesson
    {
        if(empty($lessonTitle)){
            return null;
        }
        $lesson = new Lesson();
        $lesson->setTitle($lessonTitle);
        if(!empty($course)){
            $lesson->setCourse($course);
        }
        return $lesson;
    }

    private function makeCourse(?string $courseTitle): Course
    {
        $course = new Course();
        if(!empty($courseTitle)){
            $course->setTitle($courseTitle);
        }
        return $course;
    }
}
