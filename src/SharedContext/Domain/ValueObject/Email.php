<?php

namespace App\SharedContext\Domain\ValueObject;

use InvalidArgumentException;

final class Email
{
   private string $value;

   public function __construct(string $value)
   {
      $value = trim($value);

      if ($value === '' || !filter_var($value, FILTER_VALIDATE_EMAIL)) {
         throw new InvalidArgumentException(sprintf('Invalid email: "%s"', $value));
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
}
