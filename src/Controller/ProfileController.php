<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\EditUserType;
use App\Form\RegisterUserType;
use App\Services\Helper;
use App\Repository\UserRepository;
use App\Services\UserFormManager;
use App\Services\PasswordFormManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @Route("/profile/teachers",
     *   name="profile.teachers",
     *   methods="GET")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Services\Helper $helper
     * @param \App\Repository\UserRepository $userRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function teacherViewList(Request $request, Helper $helper, UserRepository $userRepository)
    {
        $pagination = $helper->getEntitiesPaginated($userRepository->getByRoleB(user::ROLE_TEACHER), $request);

        return $this->render('profile/teachers.html.twig', [
            'users' => $pagination,
        ]);
    }

    /**
     * @Route("/profile/teachers/search", name="profile.teachers.search", methods="GET")
     * @param Request $request
     * @param Helper $helper
     * @param UserRepository $userRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function teacherSearch(Request $request, Helper $helper, UserRepository $userRepository)
    {
        $searchPhrase = $request->query->get('key');
        $pagination = $helper->getEntitiesPaginated(
            $userRepository->findBySearchAndRoleB(USER::ROLE_TEACHER, $searchPhrase),
            $request
        );

        return $this->render('profile/teachers.html.twig', [
            'users' => $pagination,
        ]);
    }

    /**
     * @Route("/profile/edit", name="profile.edit")
     * @param Request $request
     * @param UserFormManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editCurrentUser(Request $request, UserFormManager $manager)
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
     * @param UserFormManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editUser(Request $request, User $user, UserFormManager $manager)
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
     * @param PasswordFormManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function changePassword(Request $request, PasswordFormManager $manager)
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
     * @Route("/profile/add_teacher", name="profile.add_teacher")
     * @param Request $request
     * @param UserFormManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addTeacher(Request $request, UserFormManager $manager)
    {
        $user = new User();
        $form = $this->createForm(RegisterUserType::class, $user);

        if ($manager->handleAddTeacher($form, $request)) {
            $this->addFlash(
                'add_teacher',
                'Dėstytojas buvo sėkmingai pridėtas!'
            );

            return $this->redirectToRoute('profile.teachers');
        }

        return $this->render('profile/add_teacher.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
