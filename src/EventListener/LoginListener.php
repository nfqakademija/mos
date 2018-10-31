<?php

namespace App\EventListener;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use App\Entity\User;

class LoginListener
{
  private $em;

  public function __construct(EntityManagerInterface $em)
  {

    $this->em = $em;
  }

  public function onSecurityAuthenticationSuccess(AuthenticationEvent $event)
  {
    /** @var User $user */
    $user = $event->getAuthenticationToken()->getUser();
    if($user instanceof User ) { //on logout we don't get a User object
      // Update your field here.
      $user->setLastAccessDate(new \DateTime());

      // Persist the data to database.
      $this->em->persist($user);
      $this->em->flush();
  }

  }
}