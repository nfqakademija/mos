<?php

namespace App\Report;


use App\Entity\TimeSlot;
use App\Entity\LearningGroup;
use App\Repository\LearningGroupRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;

class Report
{

    /**
     * @param \App\Repository\LearningGroupRepository $gr
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     *
     * @return array
     */
    public function getParticipantsReport(LearningGroupRepository $gr, \DateTime $dateFrom, \DateTime $dateTo)
    {
        //TODO: rewrite using DQL if any performance issues

        //1. gets groups with end date on the range
//        SELECT learning_group.id, gr.starttime, gr.endtime FROM learning_group
//INNER JOIN (
//      SELECT learning_group.id, MIN(time_slot.start_time) AS starttime, MAX(time_slot.start_time) AS endtime FROM learning_group
//INNER JOIN time_slot ON learning_group.id = time_slot.learning_group_id
//GROUP BY learning_group.id
//) AS gr
//ON learning_group.id = gr.id AND gr.endtime >= '2018-11-01' AND gr.endtime <= '2019-01-01';



        $results = [];
        $groupsInPeriod = [];

        $groups = $gr->findAll();
        foreach ($groups as $group) {
            $timeSlots = $group->getTimeSlots();
            $latestTimeslot = $this->getLatestTimeslot($timeSlots);
            if (($latestTimeslot >= $dateFrom) and ($latestTimeslot <= $dateTo)) {
                $groupsInPeriod[] = $group;
            }
        }

        /** @var LearningGroup $group */
        foreach ($groupsInPeriod as $group) {
            $participants = $group->getParticipants();
            foreach ($participants as $participant) {
                $region = '';
                if (!empty($participant->getRegion())) {
                    $region = $participant->getRegion()->getTitle();
                }

                $results[] = [
                    'name' => $participant->getName(),
                    'surname' => $participant->getSurname(),
                    'birthDate' => $participant->getBirthDate(),
                    'region' => $region,
                    'livingAreaType' => $participant->getLivingAreaType(),
                    'address' => $participant->getAddress(),
                    'phone' => $participant->getPhone(),
                    'email' => $participant->getEmail(),
                    'gender' => $participant->getGender(),
                    'startDate' => $this->getEarliestTimeslot($group->getTimeSlots(), true),
                    'endDate' => $this->getLatestTimeslot($group->getTimeSlots(), true),
                    'groupId' => $group->getId(),
                ];
            }
        }
        return $results;
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
