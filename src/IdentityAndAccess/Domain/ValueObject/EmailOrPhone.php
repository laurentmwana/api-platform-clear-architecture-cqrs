<?php

namespace App\IdentityAndAccess\Domain\ValueObject;

use App\SharedContext\Domain\Enums\DeliveryChannelEnum;
use App\SharedContext\Domain\Exception\ValueObjectInvalidException;
use App\SharedContext\Domain\ValueObject\Email;
use App\SharedContext\Domain\ValueObject\Phone;

final class EmailOrPhone
{
   public function __construct(private Email|Phone $value) {}

   public static function fromString(string $input): self
   {
      $cleanedInput = trim($input);

      if (filter_var($cleanedInput, FILTER_VALIDATE_EMAIL)) {
         return new self(new Email($cleanedInput));
      }

      $cleanedPhone = (string) preg_replace('/[^0-9+]/', '', $cleanedInput);

      // Ajouter + si absent et ne commence pas par 0
      if (!str_starts_with($cleanedPhone, '+') && !str_starts_with($cleanedPhone, '0')) {
         $cleanedPhone = '+' . $cleanedPhone;
      }

      // Valider: +243XXXXXXXXX ou 0XXXXXXXXX
      if (preg_match('/^(\+243[0-9]{9}|0[0-9]{9})$/', $cleanedPhone)) {
         if (str_starts_with($cleanedPhone, '0')) {
            $cleanedPhone = '+243' . substr($cleanedPhone, 1);
         }
         return new self(new Phone($cleanedPhone));
      }

      throw new ValueObjectInvalidException('Invalid email or phone number');
   }

   public function value(): Email|Phone
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

   public function getValue(): string
   {
      return $this->value->value();
   }

   public function getDeliveryMethod(): DeliveryChannelEnum
   {
      return $this->isPhone()
         ? DeliveryChannelEnum::SMS
         : DeliveryChannelEnum::EMAIL;
   }

   public function __toString(): string
   {
      return $this->getValue();
   }
}
