<?php

namespace App\IdentityAndAccess\Domain\ValueObject;

use App\IdentityAndAccess\Domain\Enums\OtpTypeEnum;

final class OtpType
{
   private OtpTypeEnum $enum;

   public function __construct(string $value)
   {
      $this->enum = OtpTypeEnum::from($value);
   }

   public static function fromEnum(OtpTypeEnum $enum): self
   {
      $instance = new self($enum->value);
      $instance->enum = $enum;
      return $instance;
   }

   public static function magicLogin(): self
   {
      return new self(OtpTypeEnum::MAGIC_LOGIN->value);
   }

   public static function verifyEmail(): self
   {
      return new self(OtpTypeEnum::VERIFY_EMAIL->value);
   }

   public static function verifyPhone(): self
   {
      return new self(OtpTypeEnum::VERIFY_PHONE->value);
   }

   public static function passwordReset(): self
   {
      return new self(OtpTypeEnum::PASSWORD_RESET->value);
   }

   public function value(): string
   {
      return $this->enum->value;
   }

   public function toEnum(): OtpTypeEnum
   {
      return $this->enum;
   }

   public function equals(self $other): bool
   {
      return $this->enum === $other->enum;
   }

   public function getExpirationMinutes(): int
   {
      return $this->enum->getExpirationMinutes();
   }

   public function getMaxAttempts(): int
   {
      return $this->enum->getMaxAttempts();
   }

   public function isMagicLogin(): bool
   {
      return $this->enum->isMagicLogin();
   }

   public function isVerification(): bool
   {
      return $this->enum->isVerification();
   }

   public function isPasswordReset(): bool
   {
      return $this->enum->isPasswordReset();
   }

   public function __toString(): string
   {
      return $this->enum->value;
   }
}
