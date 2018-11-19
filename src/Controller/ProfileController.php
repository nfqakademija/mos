<?php

namespace App\Controller;

use App\Entity\LearningGroup;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

        $user = $this->getUser();

        return $this->render('profile/view.html.twig', [
          'user' => $user,
        ]);
    }

    /**
     * @Route("/profile/viewlist",
     *   name="profile.viewlist",
     *   methods="GET")
     * @return
     */
    public function profileViewList(EntityManagerInterface $em)
    {

        $users = $em->getRepository(User::class)->findAll();

        return $this->render('profile/viewlist.html.twig', [
          'users' => $users,
        ]);
    }
}
