<?php

namespace App\SharedContext\Domain\ValueObject;

use App\SharedContext\Domain\Exception\ValueObjectInvalidException;
use Stringable;

class Uuid implements Stringable
{
   private string $value;

   public function __construct(string $value)
   {
      if ($value === '' || strlen($value) !== 36) {
         throw new ValueObjectInvalidException('Invalid UUID.');
      }

      $this->value = $value;
   }

   public function value(): string
   {
      return $this->value;
   }

   public function __toString(): string
   {
      return $this->value;
   }

   public function equals(self $other): bool
   {
      return $this->value === $other->value;
   }
}
