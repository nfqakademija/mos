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
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="learningGroup", cascade={"persist"})
     * @Assert\Valid
     */
    private $participants;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TimeSlot", mappedBy="learningGroup", cascade={"persist"})
     * @Assert\Valid
     */
    private $timeSlots;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="learningGroupsUserTeaches")
     */
    private $teacher;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endDate;


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

    public function setAddress(?string $address): self
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
        $participant->setLearningGroup($this);

        $this->participants->add($participant);

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        $this->participants->removeElement($participant);

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
                    'date' => $timeslot->getDate(),
                    'startTime' => $timeslot->getStartTime(),
                    'duration' => $timeslot->getDuration(),
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
        $timeSlot->setLearningGroup($this);

        $this->timeSlots->add($timeSlot);

        return $this;
    }

    public function removeTimeSlot(TimeSlot $timeSlot): self
    {
        $this->timeSlots->removeElement($timeSlot);

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


    public function getEndDateByTimeSlots(bool $nullIfNotExist = true)
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
            if ($timeSlot->getDateObj() > $latestDate) {
                $latestDate = $timeSlot->getDateObj();
            }
        }

        return $latestDate;
    }

    public function getStartDateByTimeSlots(bool $nullIfNotExist = true)
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
            if ($timeSlot->getDateObj() < $earliestDate) {
                $earliestDate = $timeSlot->getDateObj();
            }
        }

        return $earliestDate;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function updateStartEndDates()
    {
        $startDate = $this->getStartDateByTimeSlots();
        $endDate = $this->getEndDateByTimeSlots();
        
        $this->setStartDate($startDate);
        $this->setEndDate($endDate);
    }
}
