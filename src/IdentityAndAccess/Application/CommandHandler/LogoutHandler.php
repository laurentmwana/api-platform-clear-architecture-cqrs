<?php

namespace App\IdentityAndAccess\Application\CommandHandler;

use App\IdentityAndAccess\Application\Command\LogoutCommand;
use App\IdentityAndAccess\Domain\Exception\InvalidJwtTokenException;
use App\IdentityAndAccess\Domain\Repository\SessionRepository;
use App\IdentityAndAccess\Domain\Service\AccessTokenManager;
use App\SharedContext\Application\Bus\Command\CommandHandler;

final class LogoutHandler implements CommandHandler
{
   public function __construct(
      private AccessTokenManager $accessTokenManager,
      private SessionRepository $sessionRepository
   ) {}

   public function __invoke(LogoutCommand $command): void
   {
      $isOk = $this->accessTokenManager
         ->invalidate($command->getJwtToken());

      if (!$isOk) {
         throw new InvalidJwtTokenException();
      }


      $user = $command->getUser();
      $ipAddress = $command->getIpAddress();
      $userAgent = $command->getUserAgent();

      if ($userAgent !== null && $ipAddress !== null) {

         $session = $this->sessionRepository
            ->findOneByUserIdAndDevice(
               $user->getId(),
               $ipAddress,
               $userAgent
            );

         if ($session !== null) {
            $session->markForLogout();

            $this->sessionRepository->save($session);
         }
      }
   }
}
