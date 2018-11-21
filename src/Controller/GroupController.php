<?php

namespace App\Controller;

use App\Entity\LearningGroup;
use App\Entity\User;
use App\Form\GroupType;
use Doctrine\ORM\EntityManagerInterface;
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
     */
    public function groupViewList(EntityManagerInterface $em)
    {
        $groups = $em->getRepository(LearningGroup::class)->findAll();

        return $this->render('group/viewlist.html.twig', [
          'groups' => $groups,
        ]);
    }

    /**
     * @Route("/group/create", name="group.create")
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
            'form' => $form->createView()
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
            'id' => $group->getId()
        ]);
    }
}
