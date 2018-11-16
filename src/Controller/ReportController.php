<?php


namespace App\Controller;


class ReportController extends AbstractController
{

    /**
     * @Route("/report/participants", name="report.participants",
     * @return
     */
    public function participants()
    {

        return $this->render('report/participants.html.twig', [
        ]);
    }
    
}