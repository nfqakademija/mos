<?php

namespace App\EventListener;

use App\Entity\TimeSlot;
use Doctrine\ORM\Event\LifecycleEventArgs;

class TimeSlotChangedListener
{
    public function postPersist(LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof TimeSlot) {
            $this->updateGroupStartEndDates($args);
        }
    }
    
    public function postRemove(LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof TimeSlot) {
            $this->updateGroupStartEndDates($args);
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof TimeSlot) {
            $this->updateGroupStartEndDates($args);
        }
    }

    private function updateGroupStartEndDates(LifecycleEventArgs $args)
    {
        /** @var TimeSlot $timeSlot */
        $timeSlot = $args->getEntity();
        $group = $timeSlot->getLearningGroup();
        $group->updateStartEndDates();
        
        $em = $args->getEntityManager();
        $em->persist($group);
        $em->flush($group);
    }
}
