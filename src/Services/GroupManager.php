<?php

namespace App\Services;

use App\Entity\LearningGroup;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class GroupManager
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * GroupFormHandler constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $group
     * @return LearningGroup|null
     */
    public function getGroup(string $group): ?LearningGroup
    {
        return $this->entityManager->getRepository(LearningGroup::class)->find($group);
    }

    /**
     * @param FormInterface $form
     * @param Request $request
     * @return bool
     */
    public function handleCreate(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $group = $form->getData();
            $this->entityManager->persist($group);

            foreach ($group->getParticipants() as $participant) {
                $participant->setLearningGroup($group);
                $participant->setRoles([User::ROLE_PARTICIPANT]);
                $this->entityManager->persist($participant);
            }

            $this->entityManager->flush();

            return true;
        }

        return false;
    }

    /**
     * @param FormInterface $form
     * @param Request $request
     * @return bool
     */
    public function handleEdit(FormInterface $form, Request $request): bool
    {
        $group = $form->getData();
        $oldParticipants = new ArrayCollection();

        foreach ($group->getParticipants() as $participant) {
            $oldParticipants->add($participant);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($oldParticipants as $participant) {
                if ($group->getParticipants()->contains($participant) === false) {
                    $this->entityManager->remove($participant);
                }
            }

            foreach ($group->getParticipants() as $participant) {
                if ($oldParticipants->contains($participant) === false) {
                    $participant->setLearningGroup($group);
                    $participant->setRoles([User::ROLE_PARTICIPANT]);
                    $this->entityManager->persist($participant);
                }
            }

            $this->entityManager->flush();

            return true;
        }

        return false;
    }
}
