<?php


namespace App\EventSubscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class LoginSubscriber implements EventSubscriberInterface {

  public static function getSubscribedEvents() {
    return array(
      SecurityEvents::INTERACTIVE_LOGIN => [
        ['saveLastAccessDate', 0]
      ]
    );
  }

  public function saveLastAccessDate(InteractiveLoginEvent $event) {


  }

}