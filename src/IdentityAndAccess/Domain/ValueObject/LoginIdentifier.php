<?php

namespace App\IdentityAndAccess\Domain\ValueObject;

use App\SharedContext\Domain\ValueObject\Email;
use App\SharedContext\Domain\ValueObject\Phone;

final class LoginIdentifier
{
   private function __construct(private Email|UserName|Phone $value) {}

   public static function fromString(string $input): self
   {
      $cleanedInput = trim($input);

      if (filter_var($cleanedInput, FILTER_VALIDATE_EMAIL)) {
         return new self(new Email($cleanedInput));
      }

      $cleanedPhone = preg_replace('/[^0-9+]/', '', $cleanedInput);

      if (preg_match('/^(\+243[0-9]{9}|0[0-9]{9})$/', $cleanedPhone)) {
         if (str_starts_with($cleanedPhone, '0')) {
            $cleanedPhone = '+243' . substr($cleanedPhone, 1);
         }
         return new self(new Phone($cleanedPhone));
      }

      return new self(new UserName($cleanedInput));
   }

   public function value(): Email|UserName|Phone
   {
      return $this->value;
   }

   public function isEmail(): bool
   {
      return $this->value instanceof Email;
   }

   public function isPhone(): bool
   {
      return $this->value instanceof Phone;
   }

   public function isUsername(): bool
   {
      return $this->value instanceof UserName;
   }

   public function getValue(): string
   {
      return $this->value->value();
   }
}
