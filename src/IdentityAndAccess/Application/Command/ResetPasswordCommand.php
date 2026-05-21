<?php

namespace App\IdentityAndAccess\Application\Command;

use App\IdentityAndAccess\Domain\ValueObject\EmailOrPhone;
use App\IdentityAndAccess\Domain\ValueObject\OtpCode;
use App\IdentityAndAccess\Domain\ValueObject\Password;

class ResetPasswordCommand
{
   public function __construct(
      private EmailOrPhone $identifier,
      private OtpCode $otpCode,
      private Password $newPassword,
   ) {}

   public function getOtpCode(): OtpCode
   {
      return $this->otpCode;
   }

   public function getNewPassword(): Password
   {
      return $this->newPassword;
   }

   public function getIdentifier(): EmailOrPhone
   {
      return $this->identifier;
   }
}
