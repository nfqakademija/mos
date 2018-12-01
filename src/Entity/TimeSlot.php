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

    public function getStartTime(): ?string
    {
        return $this->startTime != null ? $this->startTime->format('Y-m-d H:i:s') : null;
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
