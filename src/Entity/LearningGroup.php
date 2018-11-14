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
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="learningGroups")
     */
    private $participants;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\TimeSlot", cascade={"persist", "remove"})
     */
    private $timeSlots;

    /**
     * @ORM\Column(type="string", length=190)
     */
    private $address;

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
}
