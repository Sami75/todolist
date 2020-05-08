<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Item;
use App\Entity\ToDoList;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {

        $todolist = new ToDoList;
        $manager->persist($todolist);
        $manager->flush();

        $item = new Item();
        $item
        ->setName("Achat")
        ->setContent("Acheter des vêtements")
        ->setCreatedAt(new \DateTime())
        ->setItemTodolist($todolist);
        $manager->persist($item);
        $manager->flush();

        $user = new User();
        $user
        ->setEmail("sofianemiannay@gmail.com")
        ->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'bruh'
        ))
        ->setFirstname("Sofiane")
        ->setLastname("MIANNAY")
        ->setBirthday(new \DateTime('08/05/2020'))
        ->setUserTodolist($todolist);
        $manager->persist($user);
        $manager->flush();
    }
}
