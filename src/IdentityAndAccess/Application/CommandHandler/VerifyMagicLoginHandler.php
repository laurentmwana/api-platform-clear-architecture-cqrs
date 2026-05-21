<?php

namespace App\IdentityAndAccess\Application\CommandHandler;

use App\Bridges\Domain\Services\RefreshTokenManager;
use App\IdentityAndAccess\Application\Command\VerifyMagicLoginCommand;
use App\IdentityAndAccess\Application\Events\UserAuthenticatedEvent;
use App\IdentityAndAccess\Domain\Exception\UserCredentialsException;
use App\IdentityAndAccess\Domain\Repository\UserRepository;
use App\IdentityAndAccess\Domain\Service\AccessTokenManager;
use App\IdentityAndAccess\Domain\Service\OtpGenerator;
use App\IdentityAndAccess\Domain\ValueObject\OtpType;
use App\SharedContext\Application\Bus\Command\CommandHandler;
use App\SharedContext\Application\Bus\Event\EventBus;

class VerifyMagicLoginHandler implements CommandHandler
{
   public function __construct(
      private OtpGenerator $otpGenerator,
      private UserRepository $user,
      private AccessTokenManager $accessToken,
      private RefreshTokenManager $refreshToken,
      private EventBus $eventBus,
   ) {}

   /**
    * @param VerifyMagicLoginCommand $command
    * @return array{access_token:string,refresh_token:string}
    */
   public function __invoke(VerifyMagicLoginCommand $command): array
   {
      $user = $this->user->findByIdentifier($command->getIdentifier());

      if (!$user) {
         throw new UserCredentialsException();
      }

      $isConsumed = $this->otpGenerator->consume(
         $user->getId(),
         OtpType::magicLogin(),
         $command->getOtpCode()
      );

      if (!$isConsumed) {
         throw new UserCredentialsException("Code invalid or Expired");
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
}
