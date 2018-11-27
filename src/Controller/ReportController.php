<?php

namespace App\Controller;

use App\Report\Report;
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
     * @Route("/report/participants", name="report.participants",)
     * @return
     */
    public function participants(Request $request, Report $report)
    {
        $results = [];
        $reportFilterForm = $this->createForm(ReportFilterType::class);

        $reportFilterForm->handleRequest($request);

        if ($reportFilterForm->isSubmitted() && $reportFilterForm->isValid()) {
            $data = $reportFilterForm->getData();
            
            $range = $report->getRangeFromFormData($data);
            $results = $report->participantsReport($range['dateFrom'], $range['dateTo']);
        }
           
        return $this->render('report/participants.html.twig', [
          'form' => $reportFilterForm->createView(),
          'results' => $results,
        ]);
    }
}
