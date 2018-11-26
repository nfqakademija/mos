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

    public function participantsReport(\DateTime $dateFrom, \DateTime $dateTo)
    {

        $result = $this->lgr->participantsReport($dateFrom, $dateTo);

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
