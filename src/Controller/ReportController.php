<?php

namespace App\Controller;

use App\Entity\User;
use App\Report\Report;
use App\Repository\LearningGroupRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
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
     * @Route ("/report/filter", name="report.filter",)
     * @return
     */
    public function filter(Request $request, Report $report)
    {
        $defaultData = [];
        $reportFilterForm = $this->createForm(ReportFilterType::class, $defaultData);
        
        $reportFilterForm->handleRequest($request);

        if ($reportFilterForm->isSubmitted() && $reportFilterForm->isValid()) {
            $data = $reportFilterForm->getData();

            $range = $report->getRangeFromFormData($data);

            return $this->redirectToRoute('report.participants', [
              'dateFrom' => $range['dateFrom'],
              'dateTo' => $range['dateTo'],
            ]);
        }
        
        return $this->render('report/filter.html.twig', [
          'form' => $reportFilterForm->createView(),
        ]);
    }
    
    /**
     * @Route("/report/participants", name="report.participants",)
     * @return
     */
    public function participants(Request $request, LearningGroupRepository $groupRepo, Report $report)
    {
        $defaultData = [];
        $range = ['dateFrom' => null, 'dateTo' => null];
        $reportFilterForm = $this->createForm(ReportFilterType::class, $defaultData);

        $reportFilterForm->handleRequest($request);

        if ($reportFilterForm->isSubmitted() && $reportFilterForm->isValid()) {
            $data = $reportFilterForm->getData();

            $range = $report->getRangeFromFormData($data);
        }

        $results = $report->getParticipa ntsReport($groupRepo, $range['dateFrom'], $range['dateTo']);

        return $this->render('report/participants.html.twig', [
          'form' => $reportFilterForm->createView(),
          'results' => $results,
        ]);
    }
}
