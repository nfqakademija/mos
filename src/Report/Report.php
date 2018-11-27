<?php

namespace App\Report;

use App\Repository\LearningGroupRepository;
use App\Repository\UserRepository;

class Report
{
    /** @var LearningGroupRepository  */
    private $groupRepository;

    /** @var UserRepository */
    private $userRepository;

    /**
     * Report constructor.
     *
     * @param \App\Repository\LearningGroupRepository $groupRepository
     * @param \App\Repository\UserRepository $userRepository
     */
    public function __construct(LearningGroupRepository $groupRepository, UserRepository $userRepository)
    {
        $this->groupRepository = $groupRepository;
        $this->userRepository = $userRepository;
    }

    public function participantsReport(\DateTime $dateFrom, \DateTime $dateTo) : array
    {
        $participants = $this->userRepository->getParticipantsByGroupPeriod($dateFrom, $dateTo);

        return $participants;
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
