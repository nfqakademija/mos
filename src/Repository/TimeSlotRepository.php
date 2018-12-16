<?php

namespace App\Repository;

use App\Entity\TimeSlot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TimeSlot|null find($id, $lockMode = null, $lockVersion = null)
 * @method TimeSlot|null findOneBy(array $criteria, array $orderBy = null)
 * @method TimeSlot[]    findAll()
 * @method TimeSlot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimeSlotRepository extends ServiceEntityRepository implements RepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TimeSlot::class);
    }

    /**
     * Gets Doctrine Query Builder to get all records
     *
     * @param string $orderBy
     * @param string $orderType
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getAllQueryB($orderBy = 'ts.id', $orderType = 'DESC')
    {
        $queryBuilder = $this->createQueryBuilder('ts')
            ->addOrderBy($orderBy, $orderType);

        return $queryBuilder;
    }


    /**
     * Gets times slots in period plus extra data required by schedule report.
     */
    public function getTimeSlotsInPeriod(\DateTime $dateFrom, \DateTime $dateTo)
    {
        $queryBuilder = $this->getTimeSlotsInPeriodQueryB($dateFrom, $dateTo);
        $result = $queryBuilder->getQuery()->execute();

        return $result;
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param string $orderBy
     * @param string $orderType
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getTimeSlotsInPeriodQueryB($dateFrom, $dateTo, $orderBy = 'ts.id', $orderType = 'ASC')
    {
        $queryBuilder = $this->createQueryBuilder('ts')
            ->where('ts.date >= :dateFrom AND ts.date <= :dateTo')
            ->setParameter(':dateFrom', $dateFrom->format('Y-m-d'))
            ->setParameter(':dateTo', $dateTo->format('Y-m-d'))
            ->addOrderBy($orderBy, $orderType);

        return $queryBuilder;
    }
}
