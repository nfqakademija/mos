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

//    public function participantsReportSQL(\DateTime $dateFrom, \DateTime $dateTo)
//    {
//
//        $conn = $this->getEntityManager()->getConnection();
//        $sql = "SELECT `user`.learning_group_id AS groupId, name, surname,
//       region_id AS region, birth_date AS birthDate, address, phone,
//       email,living_area_type AS livingAreaType, gender, starttime AS startDate,endtime AS endDate FROM `user`
//                INNER JOIN
//                    (
//                      SELECT learning_group.id, gr.starttime, gr.endtime FROM learning_group
//                INNER JOIN (
//                      SELECT learning_group.id, MIN(time_slot.start_time) AS starttime,
//                             MAX(time_slot.start_time) AS endtime FROM learning_group
//                INNER JOIN time_slot ON learning_group.id = time_slot.learning_group_id
//                GROUP BY learning_group.id
//                ) AS gr
//                ON learning_group.id = gr.id AND gr.endtime >= '" . $dateFrom->format('Y-m-d') . "'
//                AND gr.endtime <= '" . $dateTo->format('Y-m-d') . "'
//                ) AS gr_range
//                ON `user`.learning_group_id = gr_range.id;";
//
//        $stmt = $conn->prepare($sql);
//        $stmt->execute();
//        $result = $stmt->fetchAll();
//
//        return $result;
//    }


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
          ->having('MIN(ts.startTime) >= :dateFrom')
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
