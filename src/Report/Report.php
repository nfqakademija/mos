<?php

namespace App\Report;

use App\Repository\LearningGroupRepository;

class Report
{
    /** @var LearningGroupRepository */
    private $lgr;

    /**
     * Report constructor.
     */
    public function __construct(LearningGroupRepository $lgr)
    {
        $this->lgr = $lgr;
    }

    public function participantsReport(\DateTime $dateFrom, \DateTime $dateTo) : array
    {
        $result = [];

        $groups = $this->lgr->getGroupsInPeriod($dateFrom, $dateTo);

        dump($groups);

        foreach ($groups as $group) {
            $participants = $group[0]->getParticipants();
            /** @var \App\Entity\User $participant */
            foreach ($participants as $participant) {
                $result [] = [
                  'name' => $participant->getSurname(),
                  'surname' => $participant->getSurname(),
                  'birthDate' => $participant->getBirthDate(),
                  'region' => $participant->getRegion()->getTitle(),
                  'livingAreaType' => $participant->getLivingAreaType(),
                  'address' => $participant->getAddress(),
                  'phone' => $participant->getPhone(),
                  'email' => $participant->getEmail(),
                  'gender' => $participant->getGender(),
                  'groupId' => $participant->getId(),
                  'startDate' => $group['groupStartDate'],
                  'endDate' => $group['groupEndDate'],
                ];
            }

            return $result;
        }
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
