<?php

namespace App\Controller;

use App\Entity\LearningGroup;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
