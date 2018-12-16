<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RegionRepository")
 */
class Region
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=190)
     */
    private $title;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isProblematic;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LearningGroup", mappedBy="region")
     */
    private $learningGroups;

    public function __construct()
    {
        $this->learningGroups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }


    public function getIsProblematic(): ?bool
    {
        return $this->isProblematic;
    }

    public function setIsProblematic(bool $isProblematic): self
    {
        $this->isProblematic = $isProblematic;

        return $this;
    }

    /**
     * @return Collection|LearningGroup[]
     */
    public function getLearningGroups(): Collection
    {
        return $this->learningGroups;
    }

    public function addLearningGroup(LearningGroup $learningGroup): self
    {
        if (!$this->learningGroups->contains($learningGroup)) {
            $this->learningGroups[] = $learningGroup;
            $learningGroup->setRegion($this);
        }

        return $this;
    }

    public function removeLearningGroup(LearningGroup $learningGroup): self
    {
        if ($this->learningGroups->contains($learningGroup)) {
            $this->learningGroups->removeElement($learningGroup);
            // set the owning side to null (unless already changed)
            if ($learningGroup->getRegion() === $this) {
                $learningGroup->setRegion(null);
            }
        }

        return $this;
    }
}
