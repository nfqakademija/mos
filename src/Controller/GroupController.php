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
        $groupType = new LearningGroup();
        $form = $this->createForm(GroupType::class, $groupType);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $group = new LearningGroup();
            $group->setAddress($groupType->getAddress());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($group);
            $i = 10;

            foreach($groupType->getParticipants() as $participant) {
                $user = new User();
                $user->setUsername('name' . $i);
                $user->setPassword('pw'.$i);
                $user->setName($participant['name']);
                $user->setSurname($participant['surname']);
                $user->setLearningGroup($group);
                $entityManager->persist($user);
                $i++;
            }

            $entityManager->flush();
        }

        return $this->render('group/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
