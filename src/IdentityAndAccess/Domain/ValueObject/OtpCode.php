<?php

namespace App\IdentityAndAccess\Domain\ValueObject;

use InvalidArgumentException;
use Stringable;

final class OtpCode implements Stringable
{
   private const LENGTH = 6;

   private string $value;

   public function __construct(string $value)
   {
      $value = trim($value);

      if (!preg_match('/^\d{' . self::LENGTH . '}$/', $value)) {
         throw new InvalidArgumentException(
            sprintf('Invalid OTP code: "%s". Must be %d digits.', $value, self::LENGTH)
         );
      }

      $this->value = $value;
   }

   public function value(): string
   {
      return $this->value;
   }

   public function equals(self $other): bool
   {
      return $other->value() === $this->value();
   }

   public function __toString(): string
   {
      return $this->value;
   }
}
