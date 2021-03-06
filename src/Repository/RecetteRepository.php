<?php

namespace App\Repository;

use App\Entity\Recette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Recette|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recette|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recette[]    findAll()
 * @method Recette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recette::class);
    }

    public function findLastRecettes($number)
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.creation_date', 'DESC')
            ->Where('p.online = true')
            ->setMaxResults($number)
            ->getQuery()
            ->getResult();
    }

    public function findBestRecettes($number)
    {
        return $this->createQueryBuilder('p')
        ->orderBy('p.note', 'DESC')
        ->Where('p.online = true')
        ->setMaxResults($number)
        ->getQuery()
        ->getResult();
    }

    public function findValidateOnlineRecettes()
    {
        return $this->createQueryBuilder('p')
        ->Where('p.validate = true')
        ->andWhere('p.online = true')
        ->getQuery()
        ->getResult();
    }

    public function findByRequest($query)
    {
        return $this->createQueryBuilder('p')
        ->where('p.title LIKE :title')
        ->setParameter('title', '%'.$query.'%')
        ->andWhere('p.validate = true')
        ->andWhere('p.online = true')
        ->getQuery()
        ->getResult();
    }

    public function findCategoryRecettesByRequest($query, $category)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c')
            ->addSelect('c')
            ->where('p.title LIKE :title')
            ->setParameter('title', '%'.$query.'%')
            ->andWhere('c.id = :category')
            ->setParameter('category', $category->getId())
            ->andWhere('p.validate = true')
            ->andWhere('p.online = true')
            ->getQuery()
            ->getResult();
    }

    public function findDietRecettesByRequest($query, $diet)
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.diet', 'd')
            ->addSelect('d')
            ->where('p.title LIKE :title')
            ->setParameter('title', '%'.$query.'%')
            ->andWhere('d.id = :diet')
            ->setParameter('diet', $diet->getId())
            ->andWhere('p.validate = true')
            ->andWhere('p.online = true')
            ->getQuery()
            ->getResult(); 
    }



  


    

    // /**
    //  * @return Recette[] Returns an array of Recette objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Recette
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
