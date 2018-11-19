<?php

namespace App\Controller;

use App\Entity\User;
use App\Report\Report;
use App\Repository\LearningGroupRepository;
use App\Repository\UserRepository;
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
    public function filter(Request $request)
    {
        $defaultData = [];
        $reportFilterForm = $this->createForm(ReportFilterType::class, $defaultData);
        
        $reportFilterForm->handleRequest($request);

        if ($reportFilterForm->isSubmitted() && $reportFilterForm->isValid()) {
            $data = $reportFilterForm->getData();

            return $this->redirectToRoute('report.participants', [
              'dateFrom' => $data['dateFrom']->format('Y-m-d'),
              'dateTo' => $data['dateTo']->format('Y-m-d')
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
    public function participants(Report $report, LearningGroupRepository $gr, Request $request)
    {

        if (empty($request->get('dateFrom'))) {
            $dateFrom = new \DateTime('1970-01-01');
        } else {
            try {
                $dateFrom = new \DateTime($request->get('dateFrom'));
            } catch (\Exception $e) {
                $dateFrom = new \DateTime('1970-01-01');
            }
        }

        if (empty($request->get('dateTo'))) {
            $dateTo = new \DateTime('2050-12-31');
        } else {
            try {
                $dateTo = new \DateTime($request->get('dateTo'));
            } catch (\Exception $e) {
                $dateTo = new \DateTime('2050-12-31');
            }
        }

        var_dump($dateTo);
        die;

        $results = $report->getParticipantsReport($gr, $dateFrom, $dateTo);

        return $this->render('report/participants.html.twig', [
          'results' => $results,
        ]);
    }
}
