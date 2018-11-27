<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @return User[] Returns an array of User objects
     */

    public function findByRole($role)
    {
        return $this->createQueryBuilder('u')
          ->andWhere('u.roles LIKE :val')
          ->setParameter('val', '%"' . $role . '"%')
          ->orderBy('u.id', 'ASC')
          ->getQuery()
          ->getResult();
    }

    /**
     * Gets all participants participanting in groups which ends in the period
     * plus extra starDate, endDate, groupId
     */
    public function getParticipantsByGroupPeriod(\DateTime $dateFrom, \DateTime $dateTo)
    {
        $query = $this->getQueryParticipantsByGroupPeriod($dateFrom, $dateTo);

        $result = $query->execute();

        return $result;
    }


    /**
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     * @param string $orderBy
     * @param string $orderType
     *
     * @return \Doctrine\ORM\Query
     */
    public function getQueryParticipantsByGroupPeriod($dateFrom, $dateTo, $orderBy = 'gr.id', $orderType = 'ASC')
    {
        $query = $this->createQueryBuilder('pr')
          ->innerJoin('pr.learningGroup', 'gr')

          ->addGroupBy('gr')
          ->innerJoin('gr.timeSlots', 'ts')
          ->having('MAX(ts.startTime) >= :dateFrom')
          ->andHaving('MAX(ts.startTime) <= :dateTo')

          ->setParameter(':dateFrom', $dateFrom->format('Y-m-d'))
          ->setParameter(':dateTo', $dateTo->format('Y-m-d'))

          ->addGroupBy('pr')
          ->addSelect('MIN(ts.startTime) AS startDate')
          ->addSelect('MAX(ts.startTime) AS endDate')
          ->addSelect('gr.id AS groupId')
          
          ->setMaxResults(10)
          ->setFirstResult(20)
          
          ->addOrderBy($orderBy, $orderType)

          ->getQuery();
        ;


        return $query;
    }
}
