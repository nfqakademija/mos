<?php

namespace App\Controller;

use App\Services\Helper;
use App\Services\ReportManager;
use App\Repository\RegionRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ReportFilterType;
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Knp\Component\Pager\PaginatorInterface $paginator
     * @param \App\Repository\UserRepository $ur
     * @param \App\Services\Helper $helper
     *
     * @return RedirectResponse|Response
     */
    public function participantsReport(
        Request $request,
        PaginatorInterface $paginator,
        UserRepository $ur,
        Helper $helper
    ) {
        $datesFromTo = $helper->dateFromToFromRequest($request);

        $submitButton = $request->query->get('submit_button');
        if ($submitButton === 'export') {
            return $this->redirectToRoute("report.participants.export", [
                [
                    'dateFrom' => $datesFromTo['dateFrom']->format('Y-m-d'),
                    'dateTo' => $datesFromTo['dateTo']->format('Y-m-d'),
                ]
            ]);
        }

        $page = $helper->getPageFromRequest($request);

        $query = $ur->getParticipantsByGroupPeriodQueryB($datesFromTo['dateFrom'], $datesFromTo['dateTo']);
        $pagination = $paginator->paginate($query, $page, 15);

        return $this->render('report/participants.html.twig', [
            'results' => $pagination,
            'dateFrom' => $datesFromTo['dateFrom'],
            'dateTo' => $datesFromTo['dateTo'],
        ]);
    }


    /**
     * @Route("/report/participants/export", name="report.participants.export",)
     *
     * @param ReportManager $report
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function participantsReportToExcel(Request $request, ReportManager $report, Helper $helper)
    {
        $datesFromTo = $helper->dateFromToFromRequest($request);

        $result = $report->participantsReportExportToExcel($datesFromTo['dateFrom'], $datesFromTo['dateTo']);

        // Return the excel file as an attachment
        return $this->file(
            $result['file'],
            $result['file_name'],
            ResponseHeaderBag::DISPOSITION_INLINE
        );
    }

    /**
     * @Route("/report/status", name="report.status")
     */
    public function statusReport(ReportManager $report, RegionRepository $regionRepository)
    {
        $result = $report->getStatusReport($regionRepository);

        return $this->render('report/status.html.twig', [
            'result' => $result,
        ]);
    }

    /**
     * @Route("/report/schedule", name="report.schedule")
     */
    public function scheduleReport(ReportManager $report)
    {
        $result = $report->getScheduleReport();

        return $this->render('report/schedule.html.twig', [
          'result' => $result,
        ]);
    }
}
