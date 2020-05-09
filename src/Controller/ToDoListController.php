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

            $send_email = $this->send_email($mailer);

            $item = $form->getData();
            $item->setCreatedAt(new \DateTime());
            $item->setItemTodolist($todolist);
                   
            // $this->canAddItem($item);
            if ($itemRepository->canAddItem($item) instanceof Item) {
                $manager->persist($item);
                $manager->flush();
                return $this->redirectToRoute('todolist');
            } else {
                return $this->render('todolist/add.html.twig', [
                'form' => $form->createView(),
                'error' => "Vous avez atteint la limite d'ajout d'item dans votre liste ou patientez 30 minutes",
                'send_email' => $send_email
            ]);
            }
        }

        return $this->render('todolist/add.html.twig', [
            'form' => $form->createView(),
            'error' => "",
            'send_email' => ""
        ]);
    }

    public function send_email(\Swift_Mailer $mailer){

        $user = $this->getUser();

        // date aujourd'hui
        $date = new \DateTime();
        // date - 18 ans
        $date_18 = $date->sub(new \DateInterval('P18Y'));
        
        if($user->getBirthday() >= $date_18)
        {
            
            $message = (new \Swift_Message('Nouveau message'))
            ->setFrom('sofianemiannay@gmail.com')
            ->setTo('sofianemiannay@gmail.com')
            ->setBody('Tu as ajouté une tâche !');

            $mailer->send($message);

            return true;
        }
        else
        {
            return false;
        }




        // $mailer->send($message);
    }


    //todo addUserTodoList()
}
