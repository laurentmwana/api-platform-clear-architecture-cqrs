<?php

namespace App\IdentityAndAccess\Presentation\Output;

use App\IdentityAndAccess\Domain\Enums\OtpTypeEnum;

class SendOtpCodeOutput
{
   /**
    * @param OtpTypeEnum $type
    * @return array{message:string,expires_minutes:int,attempts:int}
    */
   public static function toArray(OtpTypeEnum $type): array
   {
      return [
         'message' => self::getMessage($type),
         'expires_minutes' => $type->getExpirationMinutes(),
         'attempts' => $type->getMaxAttempts(),
      ];
   }

   private static function getMessage(OtpTypeEnum $type): string
   {
      return match ($type) {
         OtpTypeEnum::MAGIC_LOGIN => 'A magic login link has been sent to your email.',
         OtpTypeEnum::VERIFY_EMAIL => 'A verification code has been sent to your email address.',
         OtpTypeEnum::VERIFY_PHONE => 'A verification code has been sent to your phone number.',
         OtpTypeEnum::PASSWORD_RESET => 'A password reset code has been sent to your email.',
      };
   }
}
