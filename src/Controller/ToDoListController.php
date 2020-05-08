<?php

namespace App\Controller;

use App\Entity\ToDoList;
use App\Entity\Item;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ToDoListController extends AbstractController
{
    /**
     * @Route("/todolist", name="todolist")
     */
    public function index(ItemRepository $itemRepository)
    {

        return $this->render('todolist/index.html.twig', [
            'item' => $itemRepository->findAll(),
        ]);
    }

    public function addItem()
    {
        $item = new Item();
        $item
        ->setName("Achat".rand(0,10))
        ->setContent("Achat numero : ".rand(0,99))
        ->setCreatedAt(new \DateTime());
    }

    public function canAddItem($item)
    {

    }
}
