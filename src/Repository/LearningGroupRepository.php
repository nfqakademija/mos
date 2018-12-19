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
class LearningGroupRepository extends ServiceEntityRepository implements RepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LearningGroup::class);
    }
    
    /**
     * Gets Doctrine Query Builder to get all records
     *
     * @param string $orderBy
     * @param string $orderType
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getAllQueryB($orderBy = 'gr.id', $orderType = 'DESC')
    {
        $queryBuilder = $this->createQueryBuilder('gr')
            ->leftJoin('gr.region', 'region')
            ->addSelect('region')
            ->addOrderBy('gr.startDate', 'DESC')
            ->addOrderBy($orderBy, $orderType);

        return $queryBuilder;
    }

    /**
     * Gets groups in period
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     * @return mixed
     */
    public function getGroupsInPeriod(\DateTime $dateFrom, \DateTime $dateTo)
    {
        $query = $this->createQueryBuilder('gr')
            ->Where('gr.endDate >= :dateFrom AND gr.endDate <= :dateTo')
            ->setParameter(':dateFrom', $dateFrom->format('Y-m-d'))
            ->setParameter(':dateTo', $dateTo->format('Y-m-d'))
            ->addOrderBy('gr.startDate', 'DESC')
            ->getQuery();
        $result = $query->execute();

        return $result;
    }

    /**
     * Gets groups where exists timeslot in period
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     * @return mixed
     */
    public function getGroupsWithTimeSlotInPeriod(\DateTime $dateFrom, \DateTime $dateTo)
    {
        $query = $this->createQueryBuilder('gr')
          ->leftJoin('gr.timeSlots', 'ts')
          ->leftJoin('gr.region', 'region')
          ->addSelect('region') //reduces doctrine queries where region is used
          ->Where('ts.date >= :dateFrom AND ts.date <= :dateTo')
          ->setParameter(':dateFrom', $dateFrom->format('Y-m-d'))
          ->setParameter(':dateTo', $dateTo->format('Y-m-d'))
          ->addOrderBy('gr.startDate', 'DESC')
          ->getQuery();
        $result = $query->execute();

        return $result;
    }

    /**
     * Gets groups where user is teacher
     * @return mixed
     */
    public function getGroupsWhereUserIsTeacher($teacher)
    {
        $query = $this->createQueryBuilder('gr')
          ->leftJoin('gr.timeSlots', 'ts')
          ->Where('gr.teacher = :teacher')
          ->setParameter(':teacher', $teacher)
          ->addOrderBy('gr.startDate', 'DESC')
          ->getQuery();
        $result = $query->execute();

        return $result;
    }
}
