<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    // /**
    //  * @return Item[] Returns an array of Item objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Item
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function canAddItem($item)
    {
        if($this->getNumberOfItems($item->getItemTodolist()->getId()) < 10 && $this->checkDateDiff())
            return $item;
        else return null;
    }

    public function getNumberOfItems($todolist_id) {

        $numberOfItems = $this->createQueryBuilder('item')
            ->select('count(item.id)')
            ->where('item.item_todolist = ' .$todolist_id)
            ->getQuery()
            ->getSingleScalarResult();
            
        return $numberOfItems;
    }

    public function checkDateDiff() {
        $lastDate = $this->createQueryBuilder('item')
            ->select('MAX(item.createdAt)')
            ->getQuery()->getResult()[0][1];

        $seconds = abs(strtotime($lastDate) - (new \DateTime())->getTimestamp());

        return $seconds > 1800;
    }
}
