<?php

namespace App\IdentityAndAccess\Application\CommandHandler;

use App\IdentityAndAccess\Application\Command\VerifyPhoneCommand;
use App\IdentityAndAccess\Domain\Entity\User;
use App\IdentityAndAccess\Domain\Enums\OtpTypeEnum;
use App\IdentityAndAccess\Domain\ValueObject\OtpType;
use App\SharedContext\Application\Bus\Command\CommandHandler;

final class VerifyPhoneHandler extends AbstractOtpVerifier implements CommandHandler
{
   public function __invoke(
      VerifyPhoneCommand $command,
   ): OtpTypeEnum {
      return $this->handle(
         $command->getUser(),
         $command->getOtpCode(),
      );
   }

   protected function otpType(): OtpType
   {
      return OtpType::verifyPhone();
   }

   protected function isAlreadyVerified(User $user): bool
   {
      return $user->isPhoneVerified();
   }

   protected function markAsVerified(User $user): void
   {
      $user->markPhoneAsVerified();
   }

   protected function alreadyVerifiedMessage(): string
   {
      return 'Phone already verified.';
   }

   protected function invalidOtpMessage(): string
   {
      return 'Invalid or Expired phone verification code.';
   }
}
