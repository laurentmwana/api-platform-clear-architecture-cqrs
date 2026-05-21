<?php

namespace App\SharedContext\Domain\ValueObject;

use App\SharedContext\Domain\Exception\ValueObjectInvalidException;
use Stringable;

final class UserAgent implements Stringable
{
   public function __construct(
      private readonly string $value
   ) {
      if (empty($value) || strlen($value) > 512) {
         throw new ValueObjectInvalidException(
            'UserAgent must be non-empty and <= 512 characters'
         );
      }
   }

   public function value(): string
   {
      return $this->value;
   }

   public function __toString(): string
   {
      return $this->value;
   }
}
