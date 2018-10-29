<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;



/**
 * @ORM\Entity(repositoryClass="UserRepositoryOld")
 */
class User implements UserInterface //, \Serializable
{

  public const ROLE_ADMIN = 'administrator';
  public const ROLE_TEACHER = 'teacher';
  public const ROLE_PARTICIPANT = 'participant';
//  public const ROLE_TYPES = [
//    self::ROLE_ADMIN,
//    self::ROLE_TEACHER,
//    self::ROLE_PARTICIPANT,
//  ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=190, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=190, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=190)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=190, nullable=true)
     */
    private $surname;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthDate;


    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Region", inversedBy="users")
     */
    private $region;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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

    public function getRoles(): ?array
    {
      //todo: fix this
       // return $this->roles;
      return [User::ROLE_ADMIN];
    }

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
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


    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

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

  public function getSalt() {
    // TODO: Implement getSalt() method.
  }

  public function eraseCredentials() {
    // TODO: Implement eraseCredentials() method.
  }
//
//  public function serialize() {
//
//      return serialize(  [
//      $this->id,
//      $this->username,
//      $this->email,
//      $this->password
//    ]);
//
//  }
//
//  public function unserialize($serialized) {
//
//    list(
//      $this->id,
//      $this->username,
//      $this->email,
//      $this->password
//      ) = unserialize($serialized, ['allowed_classes' => false]);
//
//  }


}
