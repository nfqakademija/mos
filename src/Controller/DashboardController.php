<?php

namespace App\Controller;

use App\Repository\LearningGroupRepository;
use App\Repository\TimeSlotRepository;
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
    public function dashboard(LearningGroupRepository $groupRepository)
    {
        /** @var User $user */
        $user = $this->getUser();
        $groupsUserIsTeacher = $groupRepository->getGroupsWhereUserIsTeacher($user);


        $dateToday = new \DateTime('now');
        $groupsToday = $groupRepository->getGroupsWithTimeSlotInPeriod($dateToday, $dateToday);

        $dateTomorrow = new \DateTime('tomorrow');
        $groupsTomorrow = $groupRepository->getGroupsWithTimeSlotInPeriod($dateTomorrow, $dateTomorrow);

        $dateIn7Days = new \DateTime('now + 7 day');
        $groupsIn7Days = $groupRepository->getGroupsWithTimeSlotInPeriod($dateToday, $dateIn7Days);

        return $this->render('other/dashboard.html.twig', [
            'groupsUserIsTeacher' => $groupsUserIsTeacher,
            'groupsToday' => $groupsToday,
            'groupsTomorrow' => $groupsTomorrow,
            'groupsIn7Days' => $groupsIn7Days
        ]);
    }
}
