<?php

namespace App\Tests;

use App\Entity\Item;
use App\Entity\ToDoList;
use PHPUnit\Framework\TestCase;
use App\Repository\ItemRepository;

class AddItemTest extends TestCase
{
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


        dd($item);
        
    }
}
