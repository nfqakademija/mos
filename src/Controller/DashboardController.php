<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

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
        return $this->render('other/dashboard.html.twig', [
        ]);
    }
}
