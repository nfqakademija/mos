<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(type="string", length=190)
     * @Assert\NotBlank(message="Enter address")
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="learningGroup")
     * @Assert\Valid
     */
    private $participants;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TimeSlot", mappedBy="learningGroup")
     */
    private $timeSlots;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="learningGroupsUserTeaches")
     */
    private $teacher;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->timeSlots = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|TimeSlot[]
     */
    public function getTimeSlots(): Collection
    {
        return $this->timeSlots;
    }

    public function addTimeSlot(TimeSlot $timeSlot): self
    {
        if (!$this->timeSlots->contains($timeSlot)) {
            $this->timeSlots[] = $timeSlot;
            $timeSlot->setLearningGroup($this);
        }

        return $this;
    }

    public function removeTimeSlot(TimeSlot $timeSlot): self
    {
        if ($this->timeSlots->contains($timeSlot)) {
            $this->timeSlots->removeElement($timeSlot);
            // set the owning side to null (unless already changed)
            if ($timeSlot->getLearningGroup() === $this) {
                $timeSlot->setLearningGroup(null);
            }
        }

        return $this;
    }

    public function getTeacher(): ?User
    {
        return $this->teacher;
    }

    public function setTeacher(?User $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }


    public function getEndDate(bool $nullIfNotExist = true)
    {
        $timeSlots = $this->timeSlots;

        if (sizeof($timeSlots) === 0) {
            if ($nullIfNotExist) {
                return null;
            } else {
                return new \DateTime('1970-01-01');
            }
        }

        $latestDate = new \DateTime('1970-01-01');

        /** @var TimeSlot $timeSlot */
        foreach ($timeSlots as $timeSlot) {
            if ($timeSlot->getStartTime() > $latestDate) {
                $latestDate = $timeSlot->getStartTime();
            }
        }

        return $latestDate;
    }

    public function getStartDate(bool $nullIfNotExist = true)
    {
        $timeSlots = $this->timeSlots;
        
        if (sizeof($timeSlots) === 0) {
            if ($nullIfNotExist) {
                return null;
            } else {
                return new \DateTime('2050-12-31');
            }
        }

        $earliestDate = new \DateTime('2050-12-31');

        /** @var TimeSlot $timeSlot */
        foreach ($timeSlots as $timeSlot) {
            if ($timeSlot->getStartTime() < $earliestDate) {
                $earliestDate = $timeSlot->getStartTime();
            }
        }

        return $earliestDate;
    }
}
