<?php

namespace App\IdentityAndAccess\Domain\Enums;

use App\SharedContext\Domain\Traits\Enumerable;

enum RoleUserEnum: string
{
   use Enumerable;

   case ROLE_USER = "ROLE_USER";
   case ROLE_ADMIN = "ROLE_ADMIN";

   /**
    * @return string[]
    */
   public static function forDefault(): array
   {
      return [self::ROLE_USER->value];
   }

   /**
    * @return string[]
    */
   public static function forAdmin(): array
   {
      return [self::ROLE_USER->value, self::ROLE_ADMIN->value];
   }
}
