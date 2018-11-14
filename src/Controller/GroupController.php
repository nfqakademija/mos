<?php

namespace App\Controller;

use App\Entity\LearningGroup;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GroupController
 *
 * @package App\Controller
 */
class GroupController extends AbstractController
{
    /**
     * @Route("/group/view/{group}", name="group")
     */
    public function view(LearningGroup $group)
    {

        return $this->render('group/index.html.twig', [
//            'group' => $group,
        ]);
    }
}
