<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ReportFilterType;

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
            /** @var \DateTime $dateFrom */
            $dateFrom = $data['dateFrom'];
            if(empty($dateFrom))
                $dateFromUnix = 0;
            else {
                $dateFromUnix = $dateFrom->getTimestamp();
            }
            /** @var \DateTime $dateTo */
            $dateTo = $data['dateTo'];
            if(empty($dateTo))
                $dateToUnix =  2555555555;
            else {
                $dateToUnix = $dateTo->getTimestamp();
            }
            
            return $this->redirectToRoute('report.participants', [
              'dateFrom' => $dateFromUnix, 
              'dateTo' => $dateToUnix
            ] );
        }
        
        return $this->render('report/filter.html.twig', [
          'form' => $reportFilterForm->createView(),
        ]);
    }
    
    /**
     * @Route("/report/participants", name="report.participants",)
     * @return
     */
    public function participants(UserRepository $ur, Request $request)
    {
        $dateFrom = $request->get('dateFrom');
        $dateTo = $request->get('dateTo');
        
        //TODO: get groups by enddate range, then get groups participants
        $users =  $ur->findByRole(User::ROLE_PARTICIPANT);

        return $this->render('report/participants.html.twig', [
          'users' => $users,
        ]);
    }
}
