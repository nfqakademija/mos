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
     * Gets groups by period with extra groupStartTime and groupEndTime.
     *
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     *
     * @return mixed
     */
    public function getGroupsInPeriod(\DateTime $dateFrom, \DateTime $dateTo)
    {
        $query = $this->createQueryBuilder('gr')
          ->innerJoin('gr.timeSlots', 'ts')
          ->addGroupBy('gr')
          ->having('MAX(ts.startTime) >= :dateFrom')
          ->andHaving('MAX(ts.startTime) <= :dateTo')
          ->setParameter(':dateFrom', $dateFrom->format('Y-m-d'))
          ->setParameter(':dateTo', $dateTo->format('Y-m-d'))

          ->addSelect('MIN(ts.startTime) AS groupStartDate')
          ->addSelect('MAX(ts.startTime) AS groupEndDate')

          ->getQuery()
          ;
        $result = $query->execute();

        return $result;
    }
}
