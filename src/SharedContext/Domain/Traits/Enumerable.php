<?php

namespace App\SharedContext\Domain\Traits;

trait Enumerable
{
   /**
    * @return mixed[]
    */
   public static function values(): array
   {
      return array_map(fn(self $enum) => $enum->value, self::cases());
   }

   /**
    * @return mixed[]
    */
   public static function names(): array
   {
      return array_map(fn(self $enum) => $enum->name, self::cases());
   }
}
