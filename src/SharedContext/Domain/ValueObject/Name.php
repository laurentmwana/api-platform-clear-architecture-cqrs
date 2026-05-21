<?php

namespace App\SharedContext\Domain\ValueObject;

use App\SharedContext\Domain\Exception\ValueObjectInvalidException;
use Stringable;

final class Name implements Stringable
{
   private string $value;

   public function __construct(string $value)
   {
      $value = trim($value);

      if ($value === '') {
         throw new ValueObjectInvalidException('Name cannot be empty.');
      }

      if (mb_strlen($value) > 255) {
         throw new ValueObjectInvalidException('Name cannot be longer than 255 characters.');
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
