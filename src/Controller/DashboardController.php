<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;


/**
 * Class DashboardController
 *
 * @package App\Controller
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard",)
     */
    public function dashboard() {

        /** @var User $user */
        $user = $this->getUser();
        $groupsUserIsTeacher = $user->getLearningGroupsUserTeaches();

        return $this->render('other/dashboard.html.twig', [
          'groupsUserIsTeacher' => $groupsUserIsTeacher,
        ]);
    }
}
