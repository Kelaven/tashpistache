<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{
    private $userPasswordHasher;
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création d'un user "normal" : user@email.fr password: password
        $user = new User();

        $user->setRoles(['ROLE_USER'])
            ->setName('User')
            ->setEmail('user@email.fr')
            ->setPassword($this->userPasswordHasher->hashPassword($user, 'password'));

        $manager->persist($user);

        // Création d'un user admin : admin@email.fr password: password
        $userAdmin = new User();

        $userAdmin->setRoles(['ROLE_ADMIN'])
            ->setName('Admin')
            ->setEmail('admin@email.fr')
            ->setPassword($this->userPasswordHasher->hashPassword($userAdmin, 'password'));

        $manager->persist($userAdmin);
        $manager->flush();
    }
}
