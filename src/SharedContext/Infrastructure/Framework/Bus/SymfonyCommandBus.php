<?php

namespace App\SharedContext\Infrastructure\Framework\Bus;

use App\SharedContext\Application\Bus\Command\CommandBus;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class SymfonyCommandBus implements CommandBus
{
   use HandleTrait;

   public function __construct(MessageBusInterface $commandBus)
   {
      $this->messageBus = $commandBus;
   }

   public function dispatch(object $command): mixed
   {
      try {
         return $this->handle($command);
      } catch (HandlerFailedException $e) {
         while ($e instanceof HandlerFailedException) {
            /** @var \Throwable $e */
            $e = $e->getPrevious();
         }

         throw $e;
      }
   }
}
