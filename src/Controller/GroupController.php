<?php

namespace App\Controller;

use App\Entity\LearningGroup;
use App\Form\CreateGroupType;
use App\Form\EditGroupType;
use App\Services\GroupFormManager;
use App\Services\Helper;
use App\Repository\LearningGroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @param LearningGroupRepository $groupRepository
     * @param Request $request
     * @param Helper $helper
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function groupViewList(LearningGroupRepository $groupRepository, Request $request, Helper $helper)
    {
        $pagination = $helper->getEntitiesPaginated($groupRepository->getAllQueryB(), $request);

        return $this->render('group/viewlist.html.twig', [
            'groups' => $pagination,
        ]);
    }

    /**
     * @Route("/group/create", name="group.create")
     * @param Request $request
     * @param GroupFormManager $groupFormHandler
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createGroup(Request $request, GroupFormManager $groupFormHandler)
    {
        $group = new LearningGroup();
        $form = $this->createForm(CreateGroupType::class, $group);

        $participants = $groupFormHandler->handleCreate($form, $request);
        if ($participants !== null) {
            $this->addFlash(
                'create_group',
                'Grupė buvo sėkmingai sukurta!'
            );

            return $participants->count() > 0 ? $this->render('group/participants.html.twig', [
                'participants' => $participants->toArray()
            ]) : $this->redirectToRoute('group.viewlist');
        }

        return $this->render('group/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/group/edit/{group}", name="group.edit")
     * @param Request $request
     * @param GroupFormManager $groupFormHandler
     * @param $group
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editGroup(Request $request, GroupFormManager $groupFormHandler, LearningGroup $group)
    {
        $form = $this->createForm(EditGroupType::class, $group);

        if ($groupFormHandler->handleEdit($form, $request)) {
            $this->addFlash(
                'edit_group',
                'Grupė buvo sėkmingai atnaujinta!'
            );

            return $this->redirectToRoute('group.view', array('group' => $group->getId()));
        }

        return $this->render('group/edit.html.twig', [
            'form' => $form->createView(),
            'id' => $group->getId(),
        ]);
    }
}
