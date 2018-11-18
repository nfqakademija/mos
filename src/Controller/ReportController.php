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
            return $this->redirectToRoute('report.participants', $data);
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
        
        //var_dump($request->getQueryString());
        var_dump($request->request);
        die;
        
        $users =  $ur->findByRole(User::ROLE_PARTICIPANT);

        return $this->render('report/participants.html.twig', [
          'users' => $users,
        ]);
    }
}
