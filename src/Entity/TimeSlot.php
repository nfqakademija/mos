<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="datetime")
     */
    private $startTime;

    /**
     * @ORM\Column(type="integer")
     */
    private $durationMinutes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\LearningGroup", inversedBy="timeSlots")
     */
    private $learningGroup;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getDurationMinutes(): ?int
    {
        return $this->durationMinutes;
    }

    public function setDurationMinutes(int $durationMinutes): self
    {
        $this->durationMinutes = $durationMinutes;

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
