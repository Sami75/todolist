<?php

namespace App\Controller;

use App\Entity\ToDoList;
use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * @Route("/todolist/add", name="todolist-add")
     */
    public function addItem(EntityManagerInterface $manager, Request $request)
    {
        $form = $this->createForm(ItemType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $manager->persist($item);
            $manager->flush();

            return $this->redirectToRoute('todolist');
        }

        return $this->render('todolist/add.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    public function canAddItem($item)
    {

    }
}
