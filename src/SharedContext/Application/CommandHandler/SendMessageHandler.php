<?php

namespace App\SharedContext\Application\CommandHandler;

use App\SharedContext\Application\Bus\Message\MessageDispatcher;
use App\SharedContext\Application\Bus\Message\MessageHandler;
use App\SharedContext\Application\Command\SendMessageCommand;

class SendMessageHandler implements MessageHandler
{
   public function __construct(private MessageDispatcher $messageHandler) {}

   public function __invoke(SendMessageCommand $command): void
   {
      $this->messageHandler->send(
         $command->getMessage(),
         $command->getChannel()
      );
   }
}
