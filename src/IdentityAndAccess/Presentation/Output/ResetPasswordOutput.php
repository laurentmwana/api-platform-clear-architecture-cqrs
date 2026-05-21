<?php

namespace App\IdentityAndAccess\Presentation\Output;

class ResetPasswordOutput
{
   /**
    * @return array{message:string}
    */
   public static function toArray(): array
   {
      return [
         "message" => "Password reset successfully."
      ];
   }
}
