<?php

namespace App\Repository;

use App\Entity\LearningGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LearningGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method LearningGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method LearningGroup[]    findAll()
 * @method LearningGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LearningGroupRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LearningGroup::class);
    }

    /**
     * Gets id's of groups ending int date range 
     * (end date by the last record of the timeslots)
     * 
     * @return LearningGroup[] Returns an array of LearningGroup objects
     */
    public function findByEndDate($dateFrom, $dateTo)
    {
        //TODO: make it work. Get groups, get group timeslots and retur group id's
        return $this->createQueryBuilder('l')
//            ->andWhere('l.endDate >= :dateFrom AND l.startDate <= :dateTo')
//            ->setParameter('dateFrom', $dateFrom)
//            ->setParameter('dateTo', $dateTo)
            ->orderBy('l.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?LearningGroup
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
