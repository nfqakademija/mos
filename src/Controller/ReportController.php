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
     * @param Request $request
     * @param ReportManager $report
     * @param Helper $helper
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
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
     * @param ReportManager $report
     * @param RegionRepository $regionRepository
     * @return Response
     */
    public function statusReport(ReportManager $report, RegionRepository $regionRepository)
    {
        $status = $report->getStatusReport($regionRepository);

        return $this->render('report/status.html.twig', [
            'status' => $status,
        ]);
    }
}
