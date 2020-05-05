<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ToDoListRepository")
 */
class ToDoList
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", mappedBy="user_todolist", cascade={"persist", "remove"})
     */
    private $todolist_user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Item", mappedBy="item_todolist")
     */
    private $todolist_item;

    public function __construct()
    {
        $this->todolist_item = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTodolistUser(): ?User
    {
        return $this->todolist_user;
    }

    public function setTodolistUser(?User $todolist_user): self
    {
        $this->todolist_user = $todolist_user;

        // set (or unset) the owning side of the relation if necessary
        $newUser_todolist = null === $todolist_user ? null : $this;
        if ($todolist_user->getUserTodolist() !== $newUser_todolist) {
            $todolist_user->setUserTodolist($newUser_todolist);
        }

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getTodolistItem(): Collection
    {
        return $this->todolist_item;
    }

    public function addTodolistItem(Item $todolistItem): self
    {
        if (!$this->todolist_item->contains($todolistItem)) {
            $this->todolist_item[] = $todolistItem;
            $todolistItem->setItemTodolist($this);
        }

        return $this;
    }

    public function removeTodolistItem(Item $todolistItem): self
    {
        if ($this->todolist_item->contains($todolistItem)) {
            $this->todolist_item->removeElement($todolistItem);
            // set the owning side to null (unless already changed)
            if ($todolistItem->getItemTodolist() === $this) {
                $todolistItem->setItemTodolist(null);
            }
        }

        return $this;
    }
}
