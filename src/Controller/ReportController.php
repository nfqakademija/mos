<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{

    /**
     * @Route("/report/participants", name="report.participants",)
     * @return
     */
    public function participants(UserRepository $ur)
    {
        $users =  $ur->findByRole(User::ROLE_PARTICIPANT);

        return $this->render('report/participants.html.twig', [
          'users' => $users,
        ]);
    }
}
