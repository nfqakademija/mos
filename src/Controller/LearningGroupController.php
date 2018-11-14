<?php

namespace App\Controller;

use App\Entity\LearningGroup;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LearningGroupController
 *
 * @package App\Controller
 */
class LearningGroupController extends AbstractController
{
    /**
     * @Route("/group/view", name="learning_group")
     */
    public function view(LearningGroup $group)
    {

        return $this->render('learning_group/index.html.twig', [
            'group' => $group,
        ]);
    }
}
