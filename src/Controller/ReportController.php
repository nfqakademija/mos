<?php

namespace App\Controller;

use App\Helper\Helper;
use App\Report\Report;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ReportFilterType;

/**
 * Class ReportController
 *
 * @package App\Controller
 */
class ReportController extends AbstractController
{

    /**
     * @Route("/report/participants/filter", name="report.participants.filter")
     * @param Request $request
     * @param Report $report
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function participantsFilterForm(Request $request, Report $report)
    {
        $reportFilterForm = $this->createForm(ReportFilterType::class);
        $reportFilterForm->handleRequest($request);

        if ($reportFilterForm->isSubmitted() && $reportFilterForm->isValid()) {
            $data = $reportFilterForm->getData();

            $range = $report->getRangeFromFormData($data);

            $clickedButtonName = $reportFilterForm->getClickedButton()->getName();
            if ($clickedButtonName === 'export') {
                return $this->redirectToRoute("report.participants.export", $range);
            } else {
                return $this->redirectToRoute("report.participants", $range);
            }
        }

        return $this->render('report/participants_filter.html.twig', [
          'form' => $reportFilterForm->createView(),
        ]);
    }


    /**
     * @Route("/report/participants", name="report.participants",)
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Knp\Component\Pager\PaginatorInterface $paginator
     * @param \App\Repository\UserRepository $ur
     * @param \App\Helper\Helper $helper
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|
     * \Symfony\Component\HttpFoundation\Response
     */
    public function participantsReport(
        Request $request,
        PaginatorInterface $paginator,
        UserRepository $ur,
        Helper $helper
    ){
        $page = $helper->getPageFromRequest($request);

        try {
            $dateFromString = $request->query->get('dateFrom');
            $dateFrom = new \DateTime($dateFromString);
            $dateToString = $request->query->get('dateTo');
            $dateTo = new \DateTime($dateToString);
        } catch (\Exception $e) {
            return $this->redirectToRoute("report.participants.filter");
        }

        $query = $ur->getParticipantsByGroupPeriodQueryB($dateFrom, $dateTo);
        $pagination = $paginator->paginate($query, $page, 15, ['wrap-queries' => true]);

        return $this->render('report/participants.html.twig', [
            'results' => $pagination,
        ]);
    }


    /**
     * @Route("/report/participants/export", name="report.participants.export",)
     *
     * @param Report $report
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function participantsReportToExcel(Request $request, Report $report)
    {
        try {
            $dateFromString = $request->query->get('dateFrom');
            $dateFrom = new \DateTime($dateFromString);
            $dateToString = $request->query->get('dateTo');
            $dateTo = new \DateTime($dateToString);
        } catch (\Exception $e) {
            return $this->redirectToRoute("report.participants.filter");
        }

        $result = $report->participantsReportExportToExcel($dateFrom, $dateTo);

        // Return the excel file as an attachment
        return $this->file(
            $result['file'], $result['file_name'],
            ResponseHeaderBag::DISPOSITION_INLINE
        );
    }
}
