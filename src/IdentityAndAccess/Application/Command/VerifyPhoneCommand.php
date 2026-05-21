<?php

namespace App\IdentityAndAccess\Application\Command;

use App\IdentityAndAccess\Domain\Entity\User;
use App\IdentityAndAccess\Domain\ValueObject\OtpCode;

class VerifyPhoneCommand
{
   public function __construct(
      private User $user,
      private OtpCode $otpCode
   ) {}

   public function getOtpCode(): OtpCode
   {
      return $this->otpCode;
   }

   public function getUser(): User
   {
      return $this->user;
   }
}
