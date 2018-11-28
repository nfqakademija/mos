<?php

namespace App\Report;

use App\Repository\LearningGroupRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

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
        $range = ['dateFrom' => '', 'dateTo' => ''];

        if (!empty($data['dateFrom'])) {
            $range['dateFrom'] = ($data['dateFrom'])->format('Y-m-d');
        }

        if (!empty($data['dateTo'])) {
            $range['dateTo'] = ($data['dateTo'])->format('Y-m-d');
        }

        return $range;
    }
}
