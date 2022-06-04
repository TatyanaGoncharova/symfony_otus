<?php

namespace App\Service;


use App\Entity\User;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;

class UserService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(string $login): User
    {
        $user = new User();
        $user->setLogin($login);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function clearEntityManager(): void
    {
        $this->entityManager->clear();
    }

    public function findUser(int $id): ?User
    {
        $repository = $this->entityManager->getRepository(User::class);
        $user = $repository->find($id);

        return $user instanceof User ? $user : null;
    }

    /**
     * @return array<User>
     */
    public function findUsersByLogin(string $name): array
    {
        $repository = $this->entityManager->getRepository(User::class);

        return $repository->findBy(['login' => $name]);
    }

}