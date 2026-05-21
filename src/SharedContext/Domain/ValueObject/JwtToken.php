<?php

namespace App\SharedContext\Domain\ValueObject;

use App\SharedContext\Domain\Exception\ValueObjectInvalidException;

final readonly class JwtToken
{
   public function __construct(
      private string $value,
   ) {
      $this->ensureIsValid($value);
   }

   public function value(): string
   {
      return $this->value;
   }

   private function ensureIsValid(string $token): void
   {
      if (empty($token)) {
         throw new ValueObjectInvalidException('JWT token cannot be empty.');
      }
   }

   public function equals(self $other): bool
   {
      return $this->value === $other->value;
   }

   public function __toString(): string
   {
      return $this->value;
   }
}
