<?php

namespace App\SharedContext\Infrastructure\Framework\Bus;

use App\SharedContext\Application\Bus\Message\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;

class SymfonyMessageBus implements MessageBus
{
   public function __construct(
      private MessageBusInterface $messageBus
   ) {}

   public function dispatch(object $object): void
   {
      $this->messageBus->dispatch($object);
   }
}
