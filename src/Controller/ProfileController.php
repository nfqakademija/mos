<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Form\RegisterUserType;
use App\Form\UserRegistrationType;
use App\Helper\Helper;
use App\Repository\UserRepository;
use App\Services\ParticipantFormManager;
use Knp\Component\Pager\PaginatorInterface;
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Helper\Helper $helper
     * @param \App\Repository\UserRepository $userRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profileViewList(Request $request, Helper $helper, UserRepository $userRepository)
    {
        $pagination = $helper->getEntitiesPaginated($userRepository, $request);

        return $this->render('profile/viewlist.html.twig', [
            'users' => $pagination,
        ]);
    }

    /**
     * @Route("/profile/edit/{user}", name="profile.edit")
     * @param Request $request
     * @param User $user
     * @param ParticipantFormManager $participantManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editUser(Request $request, User $user, ParticipantFormManager $participantManager)
    {
        $form = $this->createForm(EditUserType::class, $user);

        if ($participantManager->handleEdit($form, $request)) {
            $this->addFlash(
                'edit_user',
                'Dalyvis buvo sėkmingai atnaujintas!'
            );

            return $this->redirectToRoute('profile.view.user', array('user' => $user->getId()));
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
