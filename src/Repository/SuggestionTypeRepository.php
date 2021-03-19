<?php

namespace App\Repository;

use App\Entity\SuggestionType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SuggestionType|null find($id, $lockMode = null, $lockVersion = null)
 * @method SuggestionType|null findOneBy(array $criteria, array $orderBy = null)
 * @method SuggestionType[]    findAll()
 * @method SuggestionType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SuggestionTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SuggestionType::class);
    }

    // /**
    //  * @return SuggestionType[] Returns an array of SuggestionType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SuggestionType
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
