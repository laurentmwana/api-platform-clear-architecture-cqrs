<?php

namespace App\IdentityAndAccess\Domain\ValueObject;

use App\SharedContext\Domain\Exception\ValueObjectInvalidException;
use Stringable;

final class UserName implements Stringable
{
   private string $value;

   public function __construct(string $value)
   {
      $value = trim($value);

      $this->ensureNotEmpty($value);
      $this->ensureValidLength($value);
      $this->ensureValidFormat($value);

      $this->value = $value;
   }

   private function ensureNotEmpty(string $value): void
   {
      if ($value === '') {
         throw new ValueObjectInvalidException('Name cannot be empty.');
      }
   }

   private function ensureValidLength(string $value): void
   {
      $length = mb_strlen($value);

      if ($length < 5 || $length > 12) {
         throw new ValueObjectInvalidException(
            'Name must be between 5 and 12 characters.'
         );
      }
   }

   private function ensureValidFormat(string $value): void
   {
      if (!preg_match('/^[a-z][a-z0-9]*$/', $value)) {
         throw new ValueObjectInvalidException(
            'Name must start with a lowercase letter and contain only lowercase letters and numbers.'
         );
      }
   }

   public function value(): string
   {
      return $this->value;
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
