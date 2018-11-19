<?php

namespace App\Report;


use App\Entity\TimeSlot;
use App\Entity\LearningGroup;
use App\Repository\LearningGroupRepository;
use Symfony\Component\HttpFoundation\Request;

class Report
{

    public function getParticipantsReport(LearningGroupRepository $gr, \DateTime $dateFrom, \DateTime $dateTo)
    {

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
                    'startDate' => $this->getEarliestTimeslot($group->getTimeSlots()),
                    'endDate' => $this->getLatestTimeslot($group->getTimeSlots()),
                    'groupId' => $group->getId(),
                ];
            }
        }

        return $results;
    }

    public function getRangeFromFormData($data)
    {
        $range = ['dateFrom' => '', 'dateTo' => ''];

        if (!empty($data['dateFrom'])) {
            $range['dateFrom'] = $data['dateFrom']->format('Y-m-d');
        }

        if (!empty($data['dateTo'])) {
            $range['dateTo'] = $data['dateTo']->format('Y-m-d');
        }

        return $range;
    }

    public function getRangeFromRequest(Request $request)
    {
        if (empty($request->get('dateFrom'))) {
            $dateFrom = new \DateTime('1970-01-01');
        } else {
            try {
                $dateFrom = new \DateTime($request->get('dateFrom'));
            } catch (\Exception $e) {
                $dateFrom = new \DateTime('1970-01-01');
            }
        }

        if (empty($request->get('dateTo'))) {
            $dateTo = new \DateTime('2050-12-31');
        } else {
            try {
                $dateTo = new \DateTime($request->get('dateTo'));
            } catch (\Exception $e) {
                $dateTo = new \DateTime('2050-12-31');
            }
        }

        return ['dateFrom' => $dateFrom, 'dateTo' => $dateTo];
    }
    


    private function getLatestTimeslot($timeSlots)
    {
        $lastDate = new \DateTime('1970-01-01');

        /** @var TimeSlot $timeSlot */
        foreach ($timeSlots as $timeSlot) {
            if ($timeSlot->getStartTime() > $lastDate) {
                $lastDate = $timeSlot->getStartTime();
            }
        }

        return $lastDate;
    }

    private function getEarliestTimeslot($timeSlots)
    {
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