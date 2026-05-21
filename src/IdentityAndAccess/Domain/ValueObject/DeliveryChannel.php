<?php

namespace App\IdentityAndAccess\Domain\ValueObject;

use App\SharedContext\Domain\Enums\DeliveryChannelEnum;

final class DeliveryChannel
{
   private DeliveryChannelEnum $enum;

   public function __construct(string $value)
   {
      $this->enum = DeliveryChannelEnum::from($value);
   }

   public static function fromEnum(DeliveryChannelEnum $enum): self
   {
      $instance = new self($enum->value);
      $instance->enum = $enum;
      return $instance;
   }

   public static function sms(): self
   {
      return new self(DeliveryChannelEnum::SMS->value);
   }

   public static function email(): self
   {
      return new self(DeliveryChannelEnum::EMAIL->value);
   }

   public static function whatsapp(): self
   {
      return new self(DeliveryChannelEnum::WHATSAPP->value);
   }

   public static function fromString(string $value): self
   {
      return new self($value);
   }

   public function value(): string
   {
      return $this->enum->value;
   }

   public function toEnum(): DeliveryChannelEnum
   {
      return $this->enum;
   }

   public function equals(self $other): bool
   {
      return $this->enum === $other->enum;
   }

   public function isSms(): bool
   {
      return $this->enum->isSms();
   }

   public function isEmail(): bool
   {
      return $this->enum->isEmail();
   }

   public function __toString(): string
   {
      return $this->enum->value;
   }
}
