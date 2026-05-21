<?php

namespace App\IdentityAndAccess\Presentation\Output;

use App\IdentityAndAccess\Domain\Enums\OtpTypeEnum;

class VerifyOtpCodeOutput
{
   /**
    * @return array{message:string}
    */
   public static function toArray(OtpTypeEnum $type): array
   {
      return [
         'message' => self::getMessage($type),
      ];
   }

   private static function getMessage(OtpTypeEnum $type): string
   {
      return match ($type) {
         OtpTypeEnum::MAGIC_LOGIN =>
         'Login successful.',

         OtpTypeEnum::VERIFY_EMAIL =>
         'Email verified successfully.',

         OtpTypeEnum::VERIFY_PHONE =>
         'Phone number verified successfully.',

         OtpTypeEnum::PASSWORD_RESET =>
         'Password has been reset successfully.',
      };
   }
}
