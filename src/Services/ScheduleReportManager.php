<?php
/**
 * Created by PhpStorm.
 * User: vaidas
 * Date: 18.12.15
 * Time: 13.42
 */

namespace App\Services;


use App\Repository\TimeSlotRepository;

class ScheduleReportManager extends ReportManager
{
    private $timeSlotRepository;

    public function __construct(TimeSlotRepository $timeSlotRepository)
    {
        $this->timeSlotRepository = $timeSlotRepository;
    }


    /**
     * Schedule report.
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     *
     * @return array
     */
    public function getScheduleReport(\DateTime $dateFrom, \DateTime $dateTo): array
    {
        $result = $this->timeSlotRepository->getTimeSlotsInPeriod($dateFrom, $dateTo);
        return $result;
    }
}