<?php

namespace App\Tests;

use App\Entity\Item;
use App\Entity\User;
use App\Entity\ToDoList;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Repository\ItemRepository;
use App\Repository\UserRepository;

class AddItemTest extends KernelTestCase
{
    private $entityManager;

    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testEmail()
    {
        $user = new User();
        $user->setEmail("sofianemiannay@gmail.com");
       
        $user = $this->entityManager
            ->getRepository(User::class)
            ->checkEmail($user)
        ;

        $this->assertSame(true, $user);
    }

    public function testFirstName()
    {
        $user = new User();
        $user->setFirstname("Sofiane");

        $user = $this->entityManager
            ->getRepository(User::class)
            ->checkFirstName($user)
        ;

        $this->assertSame(true, $user);
    }

    public function testLastName()
    {
        $user = new User();
        $user->setLastName("Miannay");

        $user = $this->entityManager
            ->getRepository(User::class)
            ->checkLastName($user)
        ;

        $this->assertSame(true, $user);
    }
    

    public function testLengthPwd()
    {
        $user = new User();
        $user->setPassword("bruhbruh");

        $user = $this->entityManager
            ->getRepository(User::class)
            ->checkLengthPwd($user)
        ;

        $this->assertSame(true, $user);
    }
    
    public function testAge()
    {
        $user = new User();
        $user->setBirthday(new \DateTime('08/05/1996'));

        $user = $this->entityManager
            ->getRepository(User::class)
            ->checkAge($user)
        ;

        $this->assertSame(true, $user);
    }

    public function testAddTodoList()
    {
        $user = new User();
        $user
        ->setEmail("sofianemiannay@gmail.com")
        ->setPassword(
            'bruhbruh'
        )
        ->setFirstname("Sofiane")
        ->setLastname("MIANNAY")
        ->setBirthday(new \DateTime('08/05/1996'));

        $user = $this->entityManager
        ->getRepository(User::class)
        ->canAddTodoList($user)
    ;

        $this->assertSame(true, $user);
    }
    

    public function testIsValid()
    {
        $user = new User();
        $user
        ->setEmail("sofianemiannay@gmail.com")
        ->setPassword(
            'bruhbruh'
        )
        ->setFirstname("Sofiane")
        ->setLastname("MIANNAY")
        ->setBirthday(new \DateTime('08/05/1996'));

        $user = $this->entityManager
            ->getRepository(User::class)
            ->isValid($user)
        ;

        $this->assertSame(true, $user);
    }
    
    public function testMockup()
    {
        $user = new User();
        $user
        ->setEmail("sofianemiannay@gmail.com")
        ->setPassword(
            'bruhbruh'
        )
        ->setFirstname("Sofiane")
        ->setLastname("MIANNAY")
        ->setBirthday(new \DateTime('08/05/2020'));
        
        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->expects($this->any())
        ->method('isvalid')
        ->willReturn(true);
        
        $bool = $userRepository->isValid($user);
        $this->assertSame(true, $bool);
    }

    public function testAdd()
    {
        $todolist = new ToDoList();

        $item = new Item();
        $item
        ->setName("Achat")
        ->setContent("Acheter des vêtements")
        ->setCreatedAt(new \DateTime())
        ->setItemTodolist($todolist);

        $itemRepository = $this->createMock(ItemRepository::class);
        $itemRepository->expects($this->any())
             ->method('canadditem')
             ->willReturn($item);

        $itemValidate = $itemRepository->canAddItem($item);

        $this->assertSame($item, $itemValidate);
    }
}
