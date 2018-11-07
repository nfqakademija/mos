<?php

namespace App\Controller;

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

        return $this->render('base.html.twig', [
            'user' => $user,
        ]);
    }

  /**
   * @Route("api/profile/view/{id}", name="api.profile.view.id", methods="POST")
   */
  public function apiProfileViewById($id, EntityManagerInterface $em ) {

    //$data = json_decode($request->getContent());

    $user = $em->getRepository(User::class)->find($id);


    return $this->json($this->userObjectToArray($user));

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

    $meArray = $this->userObjectToArray($me);

    return $this->json($meArray);
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

    $usersArray = [];
    foreach ($users as $user) {
      $usersArray[] = $this->userObjectToArray($user);
    }

    return $this->json($usersArray);

  }


  private function userObjectToArray(User $user) {
    $arr = [
      'username' => $user->getUsername(),
      'name' => $user->getName(),
      'surname' => $user->getSurname(),
      'birth_date' => $user->getBirthDate(),
      'email' => $user->getEmail(),
      'phone' => $user->getPhone(),
      'region' => NULL,
      'address' => $user->getAddress(),
      'reg_date' => $user->getRegistrationDate(),
      'last_access_date' => $user->getLastAccessDate(),
      'roles' => $user->getRoles(),
    ];

    if(!empty($user->getRegion())) {
     $arr['region'] = $user->getRegion()->getTitle();
    }

    return $arr;
  }

}
