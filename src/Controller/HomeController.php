<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->isGranted(User::ROLE_SUPERVISOR) ? $this->redirectToRoute('dashboard')
            : $this->redirectToRoute('profile.view');
    }
}
