<?php

namespace App\IdentityAndAccess\Domain\Enums;

use App\SharedContext\Domain\Traits\Enumerable;

enum OtpTypeEnum: string
{
   use Enumerable;

   case MAGIC_LOGIN = 'magic_login';
   case VERIFY_EMAIL = 'verify_email';
   case VERIFY_PHONE = 'verify_phone';
   case PASSWORD_RESET = 'password_reset';

   public function getExpirationMinutes(): int
   {
      return match ($this) {
         self::MAGIC_LOGIN => 10,
         self::VERIFY_EMAIL => 30,
         self::VERIFY_PHONE => 10,
         self::PASSWORD_RESET => 15,
      };
   }

   public function getMaxAttempts(): int
   {
      return match ($this) {
         self::MAGIC_LOGIN => 3,
         self::VERIFY_EMAIL => 5,
         self::VERIFY_PHONE => 3,
         self::PASSWORD_RESET => 3,
      };
   }

   public function isMagicLogin(): bool
   {
      return $this === self::MAGIC_LOGIN;
   }

   public function isVerification(): bool
   {
      return in_array($this, [self::VERIFY_EMAIL, self::VERIFY_PHONE]);
   }

   public function isPasswordReset(): bool
   {
      return $this === self::PASSWORD_RESET;
   }
}
