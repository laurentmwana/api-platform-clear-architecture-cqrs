<?php

namespace App\IdentityAndAccess\Application\CommandHandler;

use App\IdentityAndAccess\Application\Command\VerifyEmailCommand;
use App\IdentityAndAccess\Domain\Entity\User;
use App\IdentityAndAccess\Domain\ValueObject\OtpType;
use App\SharedContext\Application\Bus\Command\CommandHandler;

final class VerifyEmailHandler extends AbstractOtpVerifier implements CommandHandler
{
   public function __invoke(
      VerifyEmailCommand $command,
   ): mixed {
      return $this->handle(
         $command->getUser(),
         $command->getOtpCode(),
      );
   }

   protected function otpType(): OtpType
   {
      return OtpType::verifyEmail();
   }

   protected function isAlreadyVerified(User $user): bool
   {
      return $user->isEmailVerified();
   }

   protected function markAsVerified(User $user): void
   {
      $user->markEmailAsVerified();
   }

   protected function alreadyVerifiedMessage(): string
   {
      return 'Email already verified.';
   }

   protected function invalidOtpMessage(): string
   {
      return 'Invalid email verification code.';
   }
}
