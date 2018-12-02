<?php

namespace App\Services;

use App\Entity\LearningGroup;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class GroupManager
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * GroupManager constructor.
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
            $this->entityManager->flush();

            return true;
        }

        return false;
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

            $this->entityManager->persist($group);
            $this->entityManager->flush();

            return true;
        }

        return false;
    }
}
