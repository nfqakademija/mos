<?php

namespace App\Report;


use App\Entity\TimeSlot;
use App\Entity\LearningGroup;
use App\Entity\User;
use App\Repository\LearningGroupRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;

class Report
{


    public function participantsReport(\DateTime $dateFrom, \DateTime $dateTo,  EntityManagerInterface $em)
    {
        $conn = $em->getConnection();
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


    
    
    /**
     * @param $data
     *
     * @return \DateTime[] $range
     */
    public function getRangeFromFormData($data)
    {
        $range = ['dateFrom' => null, 'dateTo' => null];

        if (!empty($data['dateFrom'])) {
            $range['dateFrom'] = $data['dateFrom'];
        }

        if (!empty($data['dateTo'])) {
            $range['dateTo'] = $data['dateTo'];
        }

        return $range;
    }

    private function getLatestTimeslot($timeSlots, bool $nullIfNotExist = false)
    {
        if (sizeof($timeSlots) === 0) {
            if ($nullIfNotExist) {
                return null;
            } else {
                return new \DateTime('1970-01-01');
            }
        }

        $latestDate = new \DateTime('1970-01-01');

        /** @var TimeSlot $timeSlot */
        foreach ($timeSlots as $timeSlot) {
            if ($timeSlot->getStartTime() > $latestDate) {
                $latestDate = $timeSlot->getStartTime();
            }
        }

        return $latestDate;
    }

    private function getEarliestTimeslot($timeSlots, bool $nullIfNotExist = false)
    {
        if (sizeof($timeSlots) === 0) {
            if ($nullIfNotExist) {
                return null;
            } else {
                return new \DateTime('2050-12-31');
            }
        }

        $earliestDate = new \DateTime('2050-12-31');

        /** @var TimeSlot $timeSlot */
        foreach ($timeSlots as $timeSlot) {
            if ($timeSlot->getStartTime() < $earliestDate) {
                $earliestDate = $timeSlot->getStartTime();
            }
        }

        return $earliestDate;
    }
}
