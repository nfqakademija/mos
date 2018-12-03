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
class UserRepository extends ServiceEntityRepository implements RepositoryInterface
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
     * Gets Doctrine Query Builder to get all records
     *
     * @param string $orderBy
     * @param string $orderType
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getAllQueryB($orderBy = 'u.id', $orderType = 'DESC')
    {
        $queryBuilder = $this->createQueryBuilder('u')
          ->addOrderBy($orderBy, $orderType)
        ;

        return $queryBuilder;
    }

    /**
     * Gets all participants participanting in groups which ends in the period
     * plus extra starDate, endDate, groupId
     */
    public function getParticipantsByGroupPeriod(\DateTime $dateFrom, \DateTime $dateTo)
    {
        $queryBuilder = $this->getParticipantsByGroupPeriodQueryB($dateFrom, $dateTo);

        $result = $queryBuilder->getQuery()->execute();

        return $result;
    }


    /**
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     * @param string $orderBy
     * @param string $orderType
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getParticipantsByGroupPeriodQueryB($dateFrom, $dateTo, $orderBy = 'gr.id', $orderType = 'ASC')
    {
        $queryBuilder = $this->createQueryBuilder('pr')
          ->innerJoin('pr.learningGroup', 'gr')

          ->addGroupBy('gr')
          ->innerJoin('gr.timeSlots', 'ts')
          ->having('MAX(ts.date) >= :dateFrom')
          ->andHaving('MAX(ts.date) <= :dateTo')

          ->setParameter(':dateFrom', $dateFrom->format('Y-m-d'))
          ->setParameter(':dateTo', $dateTo->format('Y-m-d'))

          ->leftJoin('pr.region', 'region')
          
          ->addGroupBy('pr')
          
          ->addSelect(
              'pr.name,
              pr.surname,
              pr.birthDate,
              pr.email,
              pr.address,
              pr.livingAreaType,
              pr.phone,
              pr.gender,
              region.title AS regionTitle'
          )
          ->addSelect('MIN(ts.date) AS groupStart')
          ->addSelect('MAX(ts.date) AS groupEnd')
          ->addSelect('gr.id AS groupId')
          
          ->addOrderBy($orderBy, $orderType)
        ;


        return $queryBuilder;
    }
}
