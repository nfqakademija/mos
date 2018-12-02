<?php

namespace App\Controller;

use App\Entity\LearningGroup;
use App\Entity\User;
use App\Form\GroupType;
use App\Services\GroupManager;
use App\Helper\Helper;
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
        $pagination = $helper->getEntitiesPaginated($groupRepository, $request);

        return $this->render('group/viewlist.html.twig', [
            'groups' => $pagination,
        ]);
    }

    /**
     * @Route("/group/create", name="group.create")
     * @param Request $request
     * @param GroupManager $groupManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createGroup(Request $request, GroupManager $groupManager)
    {
        $group = new LearningGroup();
        $form = $this->createForm(GroupType::class, $group);

        if ($groupManager->handleCreate($form, $request)) {
            $this->addFlash(
                'create',
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
     * @param Request $request
     * @param GroupManager $groupManager
     * @param $group
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editGroup(Request $request, GroupManager $groupManager, $group)
    {
        if (null === $group = $groupManager->getGroup($group)) {
            throw $this->createNotFoundException('No group found for id '. $group);
        }

        $form = $this->createForm(GroupType::class, $group);

        if ($groupManager->handleEdit($form, $request)) {
            $this->addFlash(
                'edit',
                'Group was successfully updated!'
            );

            return $this->redirectToRoute('group.view', array('group' => $group->getId()));
        }

        return $this->render('group/edit.html.twig', [
            'form' => $form->createView(),
            'id' => $group->getId(),
        ]);
    }
}
