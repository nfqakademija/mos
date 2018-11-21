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

    public function participantsReport(\DateTime $dateFrom, \DateTime $dateTo)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT `user`.learning_group_id AS groupId, name, surname, region_id AS region, birth_date AS birthDate, address, phone, 
email,living_area_type AS livingAreaType, gender, starttime AS startDate, endtime AS endDate FROM `user`
                INNER JOIN
                    (
                      SELECT learning_group.id, gr.starttime, gr.endtime FROM learning_group
                INNER JOIN (
                      SELECT learning_group.id, MIN(time_slot.start_time) AS starttime, MAX(time_slot.start_time) AS endtime FROM learning_group
                INNER JOIN time_slot ON learning_group.id = time_slot.learning_group_id
                GROUP BY learning_group.id
                ) AS gr
                ON learning_group.id = gr.id AND gr.endtime >= '" . $dateFrom->format('Y-m-d') . "' AND gr.endtime <= '" . $dateTo->format('Y-m-d') . "' 
                ) AS gr_range 
                ON `user`.learning_group_id = gr_range.id;";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        return $result;
    }
}
