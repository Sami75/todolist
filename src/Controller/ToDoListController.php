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
    public function addItem(EntityManagerInterface $manager, Request $request, ItemRepository $itemRepository, \Swift_Mailer $mailer)
    {
        $form = $this->createForm(ItemType::class);
        $form->handleRequest($request);
        $user = $this->getUser();
        $todolist = $user->getUserTodolist();

        if ($form->isSubmitted() && $form->isValid()) {

            
            $item = $form->getData();
            $item->setCreatedAt(new \DateTime());
            $item->setItemTodolist($todolist);
            
            // $this->canAddItem($item);
            if ($itemRepository->canAddItem($item) instanceof Item) {
                $manager->persist($item);
                $manager->flush();
                $send_email = $itemRepository->send_email($mailer, $this->getUser());
                return $this->redirectToRoute('todolist');
            } else {
                return $this->render('todolist/add.html.twig', [
                'form' => $form->createView(),
                'error' => "Vous avez atteint la limite d'ajout d'item dans votre liste ou patientez 30 minutes",
            ]);
            }
        }

        return $this->render('todolist/add.html.twig', [
            'form' => $form->createView(),
            'error' => "",
        ]);
    }
}