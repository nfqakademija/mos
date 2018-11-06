<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
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

  /**
   * @Route("api/profile/view",
   *   name="api.profile.view",
   *   methods="GET")
   * @return
   */
  public function apiProfileViewMy()
  {

    /** @var User $me */
    $me = $this->getUser();

    $meArray = [
      'username' => $me->getUsername(),
      'name' => $me->getName(),
      'surname' => $me->getSurname(),
      'birth_date' => $me->getBirthDate(),
      'email' => $me->getEmail(),
      'phone' => $me->getPhone(),
      'region' => $me->getRegion()->getTitle(),
      'address' => $me->getAddress(),
      'reg_date' => $me->getRegistrationDate(),
      'last_access_date' => $me->getLastAccessDate(),
      'roles' => $me->getRoles(),
    ];

//    return $this->json($me);
    return $this->json($meArray);
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


  /**
   * @Route("api/profile/viewlist",
   *   name="api.profile.viewlist",
   *   methods="GET")
   * @return
   */
  public function apiProfileViewList(EntityManagerInterface $em)
  {

    $users = $em->getRepository(User::class)->findAll();

    return $this->json($users);

  }


}
