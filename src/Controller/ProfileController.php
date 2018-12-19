<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\EditUserType;
use App\Form\RegisterUserType;
use App\Form\StaffType;
use App\Services\Helper;
use App\Repository\UserRepository;
use App\Services\UserManager;
use App\Services\PasswordManager;
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
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('profile/view.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/profile/remove/{user}", name="profile.remove")
     * @param User $user
     * @param UserManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeProfile(User $user, UserManager $manager)
    {
        $manager->removeUser($user);

        $this->addFlash(
            'remove_user',
            'Vartotojas buvo sėkmingai pašalintas!'
        );

        return in_array(USER::ROLE_PARTICIPANT, $user->getRoles())
            ? $this->redirectToRoute('profile.participants')
            : $this->redirectToRoute('profile.staff');
    }

    /**
     * @Route("/profile/participants",
     *   name="profile.participants",
     *   methods="GET")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Services\Helper $helper
     * @param \App\Repository\UserRepository $userRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function participantViewList(Request $request, Helper $helper, UserRepository $userRepository)
    {
        $pagination = $helper->getEntitiesPaginated($userRepository->getByRoleB(USER::ROLE_PARTICIPANT), $request);

        return $this->render('profile/participants.html.twig', [
            'users' => $pagination,
        ]);
    }

    /**
     * @Route("/profile/participants/search", name="profile.participants.search", methods="GET")
     * @param Request $request
     * @param Helper $helper
     * @param UserRepository $userRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function participantSearch(Request $request, Helper $helper, UserRepository $userRepository)
    {
        $searchPhrase = $request->query->get('key');
        $pagination = $helper->getEntitiesPaginated(
            $userRepository->findBySearchAndRoleB(USER::ROLE_PARTICIPANT, $searchPhrase),
            $request
        );

        return $this->render('profile/participants.html.twig', [
            'users' => $pagination,
        ]);
    }

    /**
     * @Route("/profile/staff",
     *   name="profile.staff",
     *   methods="GET")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Services\Helper $helper
     * @param \App\Repository\UserRepository $userRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function staffViewList(Request $request, Helper $helper, UserRepository $userRepository)
    {
        $pagination = $helper->getEntitiesPaginated(
            $userRepository->getByRolesB(User::ROLE_TEACHER, User::ROLE_SUPERVISOR),
            $request
        );

        return $this->render('profile/staff.html.twig', [
            'users' => $pagination,
        ]);
    }

    /**
     * @Route("/profile/staff/search", name="profile.staff.search", methods="GET")
     * @param Request $request
     * @param Helper $helper
     * @param UserRepository $userRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function staffSearch(Request $request, Helper $helper, UserRepository $userRepository)
    {
        $searchPhrase = $request->query->get('key');
        $role = $request->query->get('filterValue');

        if ($role === '%' . USER::ROLE_TEACHER . '%') {
            $query = $userRepository->findBySearchAndRoleB(USER::ROLE_TEACHER, $searchPhrase);
        } elseif ($role === '%' . USER::ROLE_SUPERVISOR . '%') {
            $query = $userRepository->findBySearchAndRoleB(USER::ROLE_SUPERVISOR, $searchPhrase);
        } else {
            $query = $userRepository->findBySearchAndRolesB(
                User::ROLE_TEACHER,
                User::ROLE_SUPERVISOR,
                $searchPhrase
            );
        }

        $pagination = $helper->getEntitiesPaginated($query, $request);

        return $this->render('profile/staff.html.twig', [
            'users' => $pagination,
        ]);
    }

    /**
     * @Route("/profile/edit", name="profile.edit")
     * @param Request $request
     * @param UserManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editCurrentUser(Request $request, UserManager $manager)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditUserType::class, $user);

        if ($manager->handleEdit($form, $request)) {
            $this->addFlash(
                'edit_user',
                'Vartotojas buvo sėkmingai atnaujintas!'
            );

            return $this->redirectToRoute('profile.view');
        }

        return $this->render('profile/edit_user.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/edit/{user}", name="profile.edit.user")
     * @param Request $request
     * @param User $user
     * @param UserManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editUser(Request $request, User $user, UserManager $manager)
    {
        $form = $this->createForm(EditUserType::class, $user);

        if ($manager->handleEdit($form, $request)) {
            $this->addFlash(
                'edit_user',
                'Vartotojas buvo sėkmingai atnaujintas!'
            );

            return $this->redirectToRoute('profile.view.user', array('user' => $user->getId()));
        }

        return $this->render('profile/edit_user.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/change_password", name="profile.change_password")
     * @param Request $request
     * @param PasswordManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function changePassword(Request $request, PasswordManager $manager)
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class);

        if ($manager->handleChange($form, $request, $user)) {
            $this->addFlash(
                'edit_user',
                'Slaptažodis buvo sėkmingai pakeistas!'
            );

            return $this->redirectToRoute('profile.view');
        }

        return $this->render('profile/change_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/add_staff", name="profile.add_staff")
     * @param Request $request
     * @param UserManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addStaff(Request $request, UserManager $manager)
    {
        $user = new User();
        $form = $this->createForm(StaffType::class, $user);

        if ($manager->handleAddStaff($form, $request)) {
            $this->addFlash(
                'add_staff',
                'Personalo darbuotojas buvo sėkmingai pridėtas!'
            );

            return $this->redirectToRoute('profile.staff');
        }

        return $this->render('profile/add_staff.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
