<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/view/{user}",
     *   name="profile.view.user",
     *   methods="GET",
     *   requirements={"user" = "\d+"})
     * @var User $user
     * @return
     */
    public function profileView(User $user)
    {

        return $this->render('profile/view.html.twig', [
            'user' => $user,
        ]);
    }
    /**
     * @Route("/profile/view",
     *   name="profile.view",
     *   methods="GET")
     * @return
     */
    public function profileViewMy()
    {

      $me = $this->getUser();

      return $this->render('profile/view.html.twig', [
            'user' => $me,
      ]);
    }
}
