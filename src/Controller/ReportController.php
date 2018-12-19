<?php

namespace App\Controller;

use App\Entity\LearningGroup;
use App\Repository\LearningGroupRepository;
use App\Repository\TimeSlotRepository;
use App\Services\Helper;
use App\Services\ParticipantsReportManager;
use App\Services\ReportManager;
use App\Repository\RegionRepository;
use App\Repository\UserRepository;
use App\Services\ScheduleReportManager;
use App\Services\StatusReportManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ReportController
 *
 * @package App\Controller
 */
class ReportController extends AbstractController
{
    /**
     * @Route("/report/participants", name="report.participants",)
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param UserRepository $ur
     * @param Helper $helper
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function participantsReport(
        Request $request,
        PaginatorInterface $paginator,
        UserRepository $ur,
        Helper $helper
    ) {
        $dataFromRequest = $helper->dataFromRequest($request);

        $submitButton = $request->query->get('submit_button');
        if ($submitButton === 'export') {
            $response = $this->forward('App\Controller\ReportController::participantsReportToExcel', [
                'dateFrom' => $dataFromRequest['dateFrom'],
                'dateTo' => $dataFromRequest['dateTo'],
            ]);
        } else {
            $page = $helper->getPageFromRequest($request);
            $query = $ur->getParticipantsByGroupPeriodQueryB(
                $dataFromRequest['dateFrom'],
                $dataFromRequest['dateTo']
            );
            $pagination = $paginator->paginate($query, $page, 15);

            $response = $this->render('report/participants.html.twig', [
                'results' => $pagination,
                'dateFrom' => $dataFromRequest['dateFrom'],
                'dateTo' => $dataFromRequest['dateTo'],
            ]);
        }

        return $response;
    }


    /**
     * @Route("/report/participants/export", name="report.participants.export",)
     * @param Request $request
     * @param ParticipantsReportManager $report
     * @param Helper $helper
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function participantsReportToExcel($dateFrom, $dateTo, ParticipantsReportManager $report)
    {
        $result = $report->reportToExcel($dateFrom, $dateTo);

        // Return the excel file as an attachment
        return $this->file(
            $result['file'],
            $result['file_name'],
            ResponseHeaderBag::DISPOSITION_INLINE
        );
    }

    /**
     * @Route("/report/status", name="report.status")
     * @param StatusReportManager $report
     * @param RegionRepository $regionRepository
     * @return Response
     */
    public function statusReport(StatusReportManager $report)
    {
        $status = $report->getStatusReport();

        return $this->render('report/status.html.twig', [
            'status' => $status,
        ]);
    }

    /**
     * @Route("/report/schedule", name="report.schedule")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param TimeSlotRepository $ts
     * @param Helper $helper
     * @return Response
     * @throws \Exception
     */
    public function scheduleReport(
        Request $request,
        PaginatorInterface $paginator,
        TimeSlotRepository $ts,
        RegionRepository $regionRepository,
        Helper $helper
    ) {
        $regions = $regionRepository->getAllRegionsUsedInGroups();
        $dataFromRequest = $helper->dataFromRequest($request);
        $submitButton = $request->query->get('submit_button');
        if ($submitButton === 'export') {
            $response = $this->forward('App\Controller\ReportController::scheduleReportToExcel', [
                'dateFrom' => $dataFromRequest['dateFrom'],
                'dateTo' => $dataFromRequest['dateTo'],
                'regionIds' => $dataFromRequest['regionIds'],
            ]);
        } else {
            $page = $helper->getPageFromRequest($request);

            $query = $ts->getTimeSlotsInPeriod($dataFromRequest['dateFrom'], $dataFromRequest['dateTo'],
                $dataFromRequest['regionIds']);
            $pagination = $paginator->paginate($query, $page, 20);

            $response = $this->render('report/schedule.html.twig', [
                'results' => $pagination,
                'regions' => $regions,
                'selectedRegions' => $dataFromRequest['regionIds'],
                'dateFrom' => $dataFromRequest['dateFrom'],
                'dateTo' => $dataFromRequest['dateTo'],
            ]);
        }

        return $response;
    }

    /**
     * @Route("/report/schedule/export", name="report.schedule.export",)
     * @param $dateFrom
     * @param $dateTo
     * @param ScheduleReportManager $scheduleReportManager
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function scheduleReportToExcel($dateFrom, $dateTo, $regionIds, ScheduleReportManager $scheduleReportManager)
    {
        $result = $scheduleReportManager->reportToExcel($dateFrom, $dateTo, $regionIds);

        // Return the excel file as an attachment
        return $this->file(
            $result['file'],
            $result['file_name'],
            ResponseHeaderBag::DISPOSITION_INLINE
        );
    }
}
