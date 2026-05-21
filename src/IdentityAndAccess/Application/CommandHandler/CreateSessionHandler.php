<?php

namespace App\IdentityAndAccess\Application\CommandHandler;

use App\IdentityAndAccess\Application\Events\UserAuthenticatedEvent;
use App\IdentityAndAccess\Domain\Entity\Session;
use App\IdentityAndAccess\Domain\Repository\SessionRepository;
use App\SharedContext\Application\Bus\Event\EventHandler;
use App\SharedContext\Domain\Service\UuidGenerator;

class CreateSessionHandler implements EventHandler
{
   public function __construct(
      private UuidGenerator $uuid,
      private SessionRepository $session,
   ) {}

   public function __invoke(UserAuthenticatedEvent $event): void
   {
      $session = Session::create(
         $this->uuid->generate(),
         $event->getUserId(),
         $event->getUserAgent(),
         $event->getIpAddress()
      );

      $this->session->save($session);
   }
}
