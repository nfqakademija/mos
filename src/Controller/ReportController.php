<?php

namespace App\Controller;

use App\Report\Report;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Report\Report $report
     */
    public function participantsFilterForm(Request $request, Report $report)
    {
        $reportFilterForm = $this->createForm(ReportFilterType::class);
        $reportFilterForm->handleRequest($request);

        if ($reportFilterForm->isSubmitted() && $reportFilterForm->isValid()) {
            $data = $reportFilterForm->getData();

            $range = $report->getRangeFromFormData($data);

            return $this->redirectToRoute("report.participants", $range);
        }

        return $this->render('report/participants_filter.html.twig', [
          'form' => $reportFilterForm->createView(),
          ]);
    }


    /**
     * @Route("/report/participants", name="report.participants",)
     * @return
     */
    public function participants(Request $request, PaginatorInterface $paginator, UserRepository $ur)
    {
        $pagination = [];

        try {
            $dateFromString = $request->query->get('dateFrom');
            $dateFrom = new \DateTime($dateFromString);
            $dateToString = $request->query->get('dateTo');
            $dateTo = new \DateTime($dateToString);

        } catch (\Exception $e) {
            return $this->redirectToRoute("report.participants.filter");
        }

        $queryBuilder = $ur->getQueryBuilderParticipantsByGroupPeriod($dateFrom, $dateTo);
        $pagination = $paginator->paginate($queryBuilder, $request->getQueryString('page', 1), 13, ['wrap-queries' => true]);

        return $this->render('report/participants.html.twig', [
          'results' => $pagination,
        ]);
    }
}
