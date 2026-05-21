<?php

namespace App\SharedContext\Infrastructure\Framework\Bus;

use App\SharedContext\Application\Bus\Query\QueryBus;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class SymfonyQueryBus implements QueryBus
{
   use HandleTrait;

   public function __construct(MessageBusInterface $queryBus)
   {
      $this->messageBus = $queryBus;
   }

   public function dispatch(object $query): mixed
   {
      try {
         return $this->handle($query);
      } catch (HandlerFailedException $e) {
         while ($e instanceof HandlerFailedException) {
            /** @var \Throwable $e */
            $e = $e->getPrevious();
         }

         throw $e;
      }
   }
}
