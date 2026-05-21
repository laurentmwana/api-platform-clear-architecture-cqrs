<?php

namespace App\IdentityAndAccess\Domain\ValueObject;

use App\SharedContext\Domain\Exception\ValueObjectInvalidException;

final class RefreshTokenCode
{
   private string $value;

   public function __construct(string $value)
   {
      if (empty($value)) {
         throw new ValueObjectInvalidException("");
      }

      $this->value = $value;
   }

   public function value(): string
   {
      return $this->value;
   }
}
