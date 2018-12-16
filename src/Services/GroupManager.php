<?php

namespace App\Services;

use App\Entity\LearningGroup;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class GroupManager
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface $encoder
     */
    private $encoder;

    /**
     * GroupManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder)
    {
        $this->entityManager = $entityManager;
        $this->encoder = $encoder;
    }

    /**
     * @param FormInterface $form
     * @param Request $request
     * @return ArrayCollection|null
     */
    public function handleCreate(FormInterface $form, Request $request): ?ArrayCollection
    {
        $form->handleRequest($request);

        $plainParticipants = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $group = $form->getData();
            $plainParticipants = new ArrayCollection();

            foreach ($group->getParticipants() as $participant) {
                $plainParticipants->add($participant->getPlainUser());
            }

            foreach ($group->getParticipants() as $participant) {
                $participant->setPassword($this->encoder->encodePassword($participant, $participant->getPassword()));
                $participant->setRoles([User::ROLE_PARTICIPANT]);
            }

            $this->entityManager->persist($group);
            $this->entityManager->flush();
        }

        return $plainParticipants;
    }

    /**
     * @param PersistentCollection $items
     * @return ArrayCollection
     */
    private function setOriginals(PersistentCollection $items): ArrayCollection
    {
        $originals = new ArrayCollection();

        foreach ($items as $item) {
            $originals->add($item);
        }

        return $originals;
    }

    /**
     * @param ArrayCollection $originals
     * @param PersistentCollection $newItems
     */
    private function removeDeletedItems(ArrayCollection $originals, PersistentCollection $newItems): void
    {
        foreach ($originals as $original) {
            if (false === $newItems->contains($original)) {
                $this->entityManager->remove($original);
            }
        }
    }

    /**
     * @param ArrayCollection $originals
     * @param PersistentCollection $newItems
     */
    private function addNewParticipants(ArrayCollection $originals, PersistentCollection $newItems): void
    {
        foreach ($newItems as $newItem) {
            if(false === $originals->contains($newItem)) {
                $newItem->setPassword($this->encoder->encodePassword($newItem, $newItem->getPassword()));
                $newItem->setRoles([User::ROLE_PARTICIPANT]);
            }
        }
    }

    /**
     * @param LearningGroup $group
     */
    public function removeGroup(LearningGroup $group): void
    {
        foreach ($group->getParticipants() as $participant) {
            $this->entityManager->remove($participant);
        }

        foreach ($group->getTimeSlots() as $timeSlot) {
            $this->entityManager->remove($timeSlot);
        }
        $this->entityManager->flush();

        $this->entityManager->remove($group);
        $this->entityManager->flush();
    }

    /**
     * @param FormInterface $form
     * @param Request $request
     * @return bool
     */
    public function handleEdit(FormInterface $form, Request $request): bool
    {
        $group = $form->getData();
        $participants = $group->getParticipants();
        $timeSlots = $group->getTimeSlots();

        $originalParticipants = $this->setOriginals($participants);
        $originalTimeSlots = $this->setOriginals($timeSlots);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->removeDeletedItems($originalParticipants, $participants);
            $this->removeDeletedItems($originalTimeSlots, $timeSlots);

            $this->addNewParticipants($originalParticipants, $participants);

            $this->entityManager->persist($group);
            $this->entityManager->flush();

            return true;
        }

        return false;
    }

    /**
     * @param LearningGroup $learningGroup
     */
    public function updateStartEndDates(LearningGroup $learningGroup)
    {
        $learningGroup->updateStartEndDates();
        $this->entityManager->persist($learningGroup);
        $this->entityManager->flush();
    }
}
