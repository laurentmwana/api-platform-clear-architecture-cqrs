<?php

namespace App\IdentityAndAccess\Application\CommandHandler;

use App\Bridges\Domain\Services\RefreshTokenManager;
use App\IdentityAndAccess\Application\Command\LoginCommand;
use App\IdentityAndAccess\Application\Events\UserAuthenticatedEvent;
use App\IdentityAndAccess\Domain\Exception\UserCredentialsException;
use App\IdentityAndAccess\Domain\Repository\UserRepository;
use App\IdentityAndAccess\Domain\Service\AccessTokenManager;
use App\IdentityAndAccess\Domain\Service\PasswordHasher;
use App\SharedContext\Application\Bus\Command\CommandHandler;
use App\SharedContext\Application\Bus\Event\EventBus;

final class LoginHandler implements CommandHandler
{
   public function __construct(
      private UserRepository $repository,
      private PasswordHasher $hasher,
      private AccessTokenManager $accessToken,
      private RefreshTokenManager $refreshToken,
      private EventBus $eventBus,
   ) {}

   /**
    * @param LoginCommand $command
    * @return array{access_token:string,refresh_token:string}
    */
   public function __invoke(LoginCommand $command): array
   {
      $user = $this->repository->findByIdentifier($command->getIdentifier());

      if (!$user || !$this->isMatch($user->getPassword(), $command->getPassword())) {
         throw new UserCredentialsException();
      }

      $this->eventBus->dispatch(
         new UserAuthenticatedEvent(
            $user->getId(),
            $command->getIpAddress(),
            $command->getUserAgent()
         )
      );

      $accessToken =  $this->accessToken->generate($user);
      $refreshToken =  $this->refreshToken->generate($user);

      return [
         'access_token' => $accessToken,
         'refresh_token' => $refreshToken,
      ];
   }

   private function isMatch(string $hashPassword, string $plainPassword): bool
   {
      return $this->hasher->verify($hashPassword, $plainPassword);
   }
}
