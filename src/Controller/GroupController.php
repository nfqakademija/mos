<?php

namespace App\Controller;

use App\Entity\LearningGroup;
use App\Entity\User;
use App\Form\GroupType;
use App\Helper\Helper;
use App\Repository\LearningGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GroupController
 *
 * @package App\Controller
 */
class GroupController extends AbstractController
{

    /**
     * @Route("/group/view/{group}", name="group.view")
     * @param \App\Entity\LearningGroup $group
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function view(LearningGroup $group)
    {
        $groupDataArray = $group->toArray();
        return $this->render('group/view.html.twig', [
          'group' => $groupDataArray,
        ]);
    }

    /**
     * @Route("/group/viewlist", name="group.viewlist")
     * @param \Doctrine\ORM\EntityManagerInterface $em
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Controller\Helper $helper
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function groupViewList(LearningGroupRepository $groupRepository, Request $request, Helper $helper)
    {

        $pagination = $helper->getEntitiesPaginated($groupRepository, $request);

        dump($request);
        dump($pagination);

        return $this->render('group/viewlist.html.twig', [
          'groups' => $pagination,
        ]);
    }

    /**
     * @Route("/group/create", name="group.create")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createGroup(Request $request)
    {
        $group = new LearningGroup();
        $form = $this->createForm(GroupType::class, $group);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($group);

            foreach ($group->getParticipants() as $participant) {
                $participant->setLearningGroup($group);
                $participant->setRoles([User::ROLE_PARTICIPANT]);
                $entityManager->persist($participant);
            }

            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Group was successfully created!'
            );

            return $this->redirectToRoute('group.viewlist');
        }

        return $this->render('group/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/group/edit/{group}", name="group.edit")
     */
    public function editGroup(Request $request, $group)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $group = $entityManager->getRepository(LearningGroup::class)->find($group);

        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($group->getParticipants() as $participant) {
                $participant->setLearningGroup($group);
                $participant->setRoles([User::ROLE_PARTICIPANT]);
                $entityManager->persist($participant);
            }

            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Group was successfully updated!'
            );

            return $this->redirectToRoute('group.viewlist');
        }

        return $this->render('group/edit.html.twig', [
            'form' => $form->createView(),
            'id' => $group->getId(),
        ]);
    }
}
