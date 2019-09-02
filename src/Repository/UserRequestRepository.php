<?php

namespace App\Repository;

use App\Entity\UserRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method UserRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserRequest[]    findAll()
 * @method UserRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserRequest::class);
    }

    // /**
    //  * @return UserRequest[] Returns an array of UserRequest objects
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
    public function findOneBySomeField($value): ?UserRequest
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
