<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
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
     * Gets all participants participanting in groups which ends in the period using pure SQL query
     * plus extra starDate, endDate, groupId
     */
    public function getParticipantsByGroupPeriodBySql(\DateTime $dateFrom, \DateTime $dateTo)
    {
        //TODO: NOT TESTED OR USED. Test it and use it for export to execel if works.
        $sql = "SELECT `user`.*, region.title as region_title FROM `user` 
                INNER JOIN learning_group ON `user`.learning_group_id = learning_group.id 
                LEFT JOIN region ON `user`.region_id = region.id
                INNER JOIN (
                SELECT time_slot.id, 
                       time_slot.learning_group_id, 
                       MIN(time_slot.`date`) as group_start, 
                       MAX(time_slot.`date`) as group_end 
                FROM time_slot GROUP BY time_slot.learning_group_id 
                ) as ts ON ts.learning_group_id = learning_group.id 
                WHERE group_end >= '" . $dateFrom->format('Y-m-d') . "'
                AND group_end <= '" . $dateTo->format('Y-m-d') . "'";
        
        $result = $this->getEntityManager()->getConnection()->query($sql)->fetchAll();

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

    /**
     * Gets count of participants, where group end date is passed
     */
    public function getCountAllFinishedParticipants()
    {
        $dateNow = new \DateTime('now');
        $dateNowStr = $dateNow->format('Y-m-d');

        $sql = "SELECT COUNT(`user`.id) FROM `user` 
                INNER JOIN learning_group ON `user`.learning_group_id = learning_group.id 
                INNER JOIN (
                SELECT time_slot.id, time_slot.learning_group_id, MAX(time_slot.`date`) as max_ts_date 
                FROM time_slot GROUP BY time_slot.learning_group_id 
                ) as ts ON ts.learning_group_id = learning_group.id 
                WHERE max_ts_date <= '" . $dateNowStr . "'";
        
        $result = $this->getEntityManager()->getConnection()->query($sql)->fetch(\Doctrine\DBAL\FetchMode::NUMERIC);
        
        return $result[0];
    }

    /**
     * Gets count of participants older thant $minAge, where group end date is passed
     */
    public function getOlderThan(int $minAge = 0)
    {
        $dateNow = new \DateTime('now');
        $dateNowStr = $dateNow->format('Y-m-d');
        $birthDate = new \DateTime('now - ' . $minAge . ' year');
        $birthDateStr = $birthDate->format('Y-m-d');

        $sql = "SELECT COUNT(`user`.id) FROM `user`
                INNER JOIN learning_group 
                ON `user`.learning_group_id = learning_group.id AND `user`.birth_date < '" . $birthDateStr . "' 
                INNER JOIN (SELECT time_slot.id, time_slot.learning_group_id, MAX(time_slot.`date`) as max_ts_date 
                FROM time_slot 
                GROUP BY time_slot.learning_group_id ) as ts 
                ON ts.learning_group_id = learning_group.id 
                WHERE max_ts_date <= '" . $dateNowStr . "'";
        
        $result = $this->getEntityManager()->getConnection()->query($sql)->fetch(\Doctrine\DBAL\FetchMode::NUMERIC);

        return $result[0];
    }
    
    /**
     * Gets count of participants older thant $minAge, where group end date is passed
     */
    public function getOlderThanAndInAreaType(int $minAge = 0, string $livingAreaType = 'kaimas')
    {
        $dateNow = new \DateTime('now');
        $dateNowStr = $dateNow->format('Y-m-d');
        $birthDate = new \DateTime('now - ' . $minAge . ' year');
        $birthDateStr = $birthDate->format('Y-m-d');

        $sql = "SELECT COUNT(`user`.id) FROM `user`
                INNER JOIN learning_group 
                ON `user`.learning_group_id = learning_group.id 
                AND `user`.birth_date < '" . $birthDateStr . "' AND `user`.living_area_type = '" . $livingAreaType . "' 
                INNER JOIN (SELECT time_slot.id, time_slot.learning_group_id, MAX(time_slot.`date`) as max_ts_date 
                FROM time_slot 
                GROUP BY time_slot.learning_group_id ) as ts 
                ON ts.learning_group_id = learning_group.id 
                WHERE max_ts_date <= '" . $dateNowStr . "'";
        
        $result = $this->getEntityManager()->getConnection()->query($sql)->fetch(\Doctrine\DBAL\FetchMode::NUMERIC);

        return $result[0];
    }
    
    /**
     * Gets count of participants older thant $minAge, where group end date is passed
     */
    public function getOlderThanAndIsGender(int $minAge = 0, string $gender = 'moteris')
    {
        $dateNow = new \DateTime('now');
        $dateNowStr = $dateNow->format('Y-m-d');
        $birthDate = new \DateTime('now - ' . $minAge . ' year');
        $birthDateStr = $birthDate->format('Y-m-d');

        $sql = "SELECT COUNT(`user`.id) FROM `user`
                INNER JOIN learning_group 
                ON `user`.learning_group_id = learning_group.id 
                AND `user`.birth_date < '" . $birthDateStr . "' AND `user`.gender = '" . $gender . "' 
                INNER JOIN (SELECT time_slot.id, time_slot.learning_group_id, MAX(time_slot.`date`) as max_ts_date 
                FROM time_slot 
                GROUP BY time_slot.learning_group_id ) as ts 
                ON ts.learning_group_id = learning_group.id 
                WHERE max_ts_date <= '" . $dateNowStr . "'";
        
        $result = $this->getEntityManager()->getConnection()->query($sql)->fetch(\Doctrine\DBAL\FetchMode::NUMERIC);

        return $result[0];
    }
    
    
    /**
     * Counts participants from finished groups in regions by regionId
     */
    public function getParticipantsCountInRegionId(int $regionId)
    {
        $dateNow = new \DateTime('now');
        $dateNowStr = $dateNow->format('Y-m-d');

        $sql = "SELECT COUNT(`user`.id) FROM `user`
                INNER JOIN learning_group 
                ON `user`.learning_group_id = learning_group.id 
                AND `user`.region_id = '" . $regionId . "' 
                INNER JOIN (SELECT time_slot.id, time_slot.learning_group_id, MAX(time_slot.`date`) as max_ts_date 
                FROM time_slot 
                GROUP BY time_slot.learning_group_id ) as ts 
                ON ts.learning_group_id = learning_group.id 
                WHERE max_ts_date <= '" . $dateNowStr . "'";
        
        $result = $this->getEntityManager()->getConnection()->query($sql)->fetch(\Doctrine\DBAL\FetchMode::NUMERIC);

        return $result[0];
    }
}
