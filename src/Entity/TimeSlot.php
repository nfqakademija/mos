<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TimeSlotRepository")
 */
class TimeSlot
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="Enter date")
     */
    private $date;

    /**
     * @ORM\Column(type="time")
     * @Assert\NotBlank(message="Enter start time")
     */
    private $startTime;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Enter duration in minutes")
     */
    private $duration;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\LearningGroup", inversedBy="timeSlots")
     */
    private $learningGroup;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?string
    {
        return $this->date != null ? $this->date->format('Y-m-d') : null;
    }

    public function getDateObj(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate($date): self
    {
        try {
            $this->date = new \DateTime($date);
        } catch (\Exception $e) {
            //Do Nothing
        }

        return $this;
    }

    public function getStartTime(): ?string
    {
        return $this->startTime != null ? $this->startTime->format('H:i') : null;
    }

    public function setStartTime($startTime): self
    {
        try {
            $this->startTime = new \DateTime($startTime);
        } catch (\Exception $e) {
            //Do Nothing
        }

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getLearningGroup(): ?LearningGroup
    {
        return $this->learningGroup;
    }

    public function setLearningGroup(?LearningGroup $learningGroup): self
    {
        $this->learningGroup = $learningGroup;

        return $this;
    }
}
