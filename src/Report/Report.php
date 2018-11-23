<?php

namespace App\Report;

use Doctrine\ORM\EntityManagerInterface;

class Report
{


    public function participantsReport(\DateTime $dateFrom, \DateTime $dateTo, EntityManagerInterface $em)
    {
        $conn = $em->getConnection();
        $sql = "SELECT `user`.learning_group_id AS groupId, name, surname, 
       region_id AS region, birth_date AS birthDate, address, phone,
       email,living_area_type AS livingAreaType, gender, starttime AS startDate,endtime AS endDate FROM `user`
                INNER JOIN
                    (
                      SELECT learning_group.id, gr.starttime, gr.endtime FROM learning_group
                INNER JOIN (
                      SELECT learning_group.id, MIN(time_slot.start_time) AS starttime,
                             MAX(time_slot.start_time) AS endtime FROM learning_group
                INNER JOIN time_slot ON learning_group.id = time_slot.learning_group_id
                GROUP BY learning_group.id
                ) AS gr
                ON learning_group.id = gr.id AND gr.endtime >= '" . $dateFrom->format('Y-m-d') . "' 
                AND gr.endtime <= '" . $dateTo->format('Y-m-d') . "' 
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
}
