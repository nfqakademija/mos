<?php

namespace App\Report;


use App\Entity\TimeSlot;
use App\Entity\LearningGroup;
use App\Repository\LearningGroupRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;

class Report
{

    public function getParticipantsReport(LearningGroupRepository $gr, \DateTime $dateFrom, \DateTime $dateTo)
    {
        //TODo: rebuild using DQL

        $results = [];
        $groupsInPeriod = [];

        $groups = $gr->findAll();
        foreach ($groups as $group) {
            $endDate = $group->getEndDate(false);
            if (($endDate >= $dateFrom) and ($endDate <= $dateTo)) {
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
                    'startDate' => $group->getStartDate(),
                    'endDate' => $group->getEndDate(),
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

 
}
