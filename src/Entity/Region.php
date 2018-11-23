<?php

namespace App\Entity;

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
    private $isCity;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isProblematic;

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

    public function getIsCity(): ?bool
    {
        return $this->isCity;
    }

    public function setIsCity(bool $isCity): self
    {
        $this->isCity = $isCity;

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
}
