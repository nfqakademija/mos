<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LearningGroupRepository")
 */
class LearningGroup
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\TimeSlot", cascade={"persist",
     *   "remove"})
     */
    private $timeSlots;

    /**
     * @ORM\Column(type="string", length=190)
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="learningGroup")
     */
    private $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getTimeSlots(): ?TimeSlot
    {
        return $this->timeSlots;
    }

    public function setTimeSlots(?TimeSlot $timeSlots): self
    {
        $this->timeSlots = $timeSlots;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setLearningGroup($this);
        }

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        if ($this->participants->contains($participant)) {
            $this->participants->removeElement($participant);
            // set the owning side to null (unless already changed)
            if ($participant->getLearningGroup() === $this) {
                $participant->setLearningGroup(null);
            }
        }

        return $this;
    }


    public function toArray()
    {
        $arr = [
          'id' => $this->getId(),
          'timeslots' => [],
          'address' => $this->getAddress(),
          'participants' => [],
        ];

        $timeslots = $this->getTimeSlots();
        if (!empty($timeslots)) {
            /** @var \App\Entity\TimeSlot $timeslot */
            foreach ($timeslots as $timeslot) {
                $arr['timeslots'][] = [
                  'id' => $timeslot->getId(),
                  'startTime' => $timeslot->getStartTime(),
                  'durationMinutes' => $timeslot->getDurationMinutes(),
                ];
            }
        }
        $participants = $this->getParticipants();
        if (!empty($participants)) {
            foreach ($participants as $participant) {
                $arr['participants'][] = $participant->toArray();
            }
        }

        return $arr;
    }
}
