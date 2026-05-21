<?php

namespace App\SharedContext\Infrastructure\Framework\Bus;

use App\SharedContext\Application\Bus\Event\EventBus;
use Symfony\Component\Messenger\MessageBusInterface;

class SymfonyEventBus implements EventBus
{
   public function __construct(
      private MessageBusInterface $eventBus
   ) {}

   public function dispatch(object $event): void
   {
      $this->eventBus->dispatch($event);
   }
}
