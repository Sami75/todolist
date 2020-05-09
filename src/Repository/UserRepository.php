<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function isValid($user) {
        if($this->checkEmail($user) && $this->checkFirstName($user) && $this->checkLastName($user) && $this->checkLengthPwd($user) && $this->checkAge($user))
            return true;
        else return false;
    }

    public function checkEmail($user) {
        if(preg_match('/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/', $user->getEmail())) return true;
        else return false;
    }

    public function checkFirstName($user) {
        if($user->getFirstName()) return true;
        else return false;
    }

    public function checkLastName($user) {
        if($user->getLastName()) return true;
        else return false;
    }

    public function checkLengthPwd($user) {
        if(strlen($user->getPassword()) >= 8 && strlen($user->getPassword()) <= 40) return true;
        else return false;
    }

    public function checkAge($user) {
        $birth = new \DateTime($user->getBirthday()->format("Y-m-d"));
        $now = new \DateTime();

        if($now->diff($birth)->y >= 13) return true;
        else return false;
    }

    public function canAddTodoList($user) {
        if(!$user->getUserTodolist() && $this->isValid($user)) return true;
        else return false;
    }
}
