<?php

namespace App\SharedContext\Domain\ValueObject;

use App\SharedContext\Domain\Exception\ValueObjectInvalidException;
use Stringable;

final class Phone implements Stringable
{
   private string $value;

   public function __construct(string $value)
   {
      $value = trim($value);

      $cleaned = (string) preg_replace('/[^0-9+]/', '', $value);

      if ($cleaned === '') {
         throw new ValueObjectInvalidException(
            'Phone number cannot be empty.'
         );
      }

      if (!str_starts_with($cleaned, '+')) {
         $cleaned = '+' . $cleaned;
      }

      if (!preg_match('/^\+243[0-9]{9}$/', $cleaned)) {
         throw new ValueObjectInvalidException(
            sprintf(
               'Invalid phone number: "%s". Must be like +243812345678',
               $value
            )
         );
      }

      $this->value = $cleaned;
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
