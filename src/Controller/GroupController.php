<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GroupController extends AbstractController
{
    /**
     * @Route("/group/view", name="group")
     */
    public function view()
    {
        return $this->render('group/index.html.twig', [
            'controller_name' => 'GroupController',
        ]);
    }



}
