<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface
{

    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_TEACHER = 'ROLE_TEACHER';
    public const ROLE_PARTICIPANT = 'ROLE_PARTICIPANT';
    public const ROLE_INSPECTOR = 'ROLE_INSPECTOR';

    //  public const ROLES = [
    //    self::ROLE_ADMIN,
    //    self::ROLE_TEACHER,
    //    self::ROLE_PARTICIPANT,
    //    self::ROLE_INSPECTOR,
    //  ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=190, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=190, nullable=true)
     */
    private $surname;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $birthDate;

    /**
     * @ORM\Column(type="string", length=190, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Region")
     */
    private $region;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registrationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastAccessDate;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\LearningGroup", mappedBy="participants")
     */
    private $learningGroups;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\LearningGroup", inversedBy="participants")
     */
    private $learningGroup;

    public function __construct()
    {
        $this->learningGroups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(\DateTimeInterface $registrationDate): self
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    public function getLastAccessDate(): ?\DateTimeInterface
    {
        return $this->lastAccessDate;
    }

    public function setLastAccessDate(?\DateTimeInterface $lastAccessDate): self
    {
        $this->lastAccessDate = $lastAccessDate;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->registrationDate = new \DateTime();
    }

    public function toArray()
    {
        $arr = [
          'username' => $this->getUsername(),
          'name' => $this->getName(),
          'surname' => $this->getSurname(),
          'birth_date' => $this->getBirthDate(),
          'email' => $this->getEmail(),
          'phone' => $this->getPhone(),
          'region' => null,
          'address' => $this->getAddress(),
          'reg_date' => $this->getRegistrationDate(),
          'last_access_date' => $this->getLastAccessDate(),
          'roles' => $this->getRoles(),
        ];

        if (!empty($this->getRegion())) 
        {
            $arr['region'] = $this->getRegion()->getTitle();
        }

        return $arr;
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
