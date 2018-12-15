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
        return $this->isGranted(User::ROLE_TEACHER) ? $this->redirectToRoute('group.viewlist')
            : $this->redirectToRoute('profile.view');
    }
}
