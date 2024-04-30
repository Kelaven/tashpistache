<?php

namespace App\DataFixtures;

use App\Entity\Task;
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
            ->setPassword($this->userPasswordHasher->hashPassword($user, 'password'))
            ->setCreatedAt(new \DateTimeImmutable());

        $manager->persist($user);
        $this->addReference('user_1', $user); // pour récupérer la références pour les fixtures des tâches et les rattacher à cet utilisateur

        // Création d'un user admin : admin@email.fr password: password
        $userAdmin = new User();

        $userAdmin->setRoles(['ROLE_ADMIN'])
            ->setName('Admin')
            ->setEmail('admin@email.fr')
            ->setPassword($this->userPasswordHasher->hashPassword($userAdmin, 'password'))
            ->setCreatedAt(new \DateTimeImmutable());

        $manager->persist($userAdmin);


        // Flush pour s'assurer que les utilisateurs sont enregistrés et disposent d'un ID
        $manager->flush();





        // Création des tâches
        for ($count = 0; $count < 10; $count++) { // cette boucle est créée pour créer 10 tâches
            $task = new Task();

            $task->setTitle('Titre' . $count)
            ->setDescription('Description' . $count)
            ->setStart(new \DateTime('2024-06-01'))
            ->setEnd(new \DateTime('2024-06-30'))
            ->setCreatedAt(new \DateTimeImmutable());
            $user = $this->getReference('user_1'); // récupérer l'id 1 grâce à la méthode getReference des utilisateurs (grâce à l'ObjectManager) et le stocker dans $user
            $task->setUser($user); // rattacher les tâches à l'utilisateur 1

            $manager->persist($task);
        }




        // Flush pour les tâches
        $manager->flush();
    }
}
