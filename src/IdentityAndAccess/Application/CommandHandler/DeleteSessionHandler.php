<?php

namespace App\IdentityAndAccess\Application\CommandHandler;

use App\IdentityAndAccess\Application\Command\DeleteSessionCommand;
use App\IdentityAndAccess\Domain\Exception\SessionNotFoundException;
use App\IdentityAndAccess\Domain\Repository\SessionRepository;
use App\SharedContext\Application\Bus\Command\CommandHandler;
use App\IdentityAndAccess\Domain\Service\CurrentPassword;

class DeleteSessionHandler implements CommandHandler
{
   public function __construct(
      private CurrentPassword $password,
      private SessionRepository $session
   ) {}

   public function __invoke(DeleteSessionCommand $command): void
   {
      $user = $command->getUser();

      $this->password->confirm($user->getPassword(), $command->getPassword());

      $sessionId = $command->getSessionId();

      $session = $this->session->findOneByIdAndUserId($sessionId, $user->getId());

      if (!$session) {
         throw new SessionNotFoundException();
      }

      $this->session->remove($session);
   }
}
