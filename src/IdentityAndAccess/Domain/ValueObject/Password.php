<?php

namespace App\IdentityAndAccess\Domain\ValueObject;

use App\SharedContext\Domain\Exception\ValueObjectInvalidException;
use App\IdentityAndAccess\Domain\Service\PasswordHasher;
use Stringable;

final class Password implements Stringable
{
   private string $value;

   private function __construct(string $hashedPassword)
   {
      $this->value = $hashedPassword;
   }

   public function changeValue(string $hasher): self
   {
      $hasher = trim($hasher);

      if ($hasher === '') {
         throw new ValueObjectInvalidException('Password cannot be empty.');
      }

      $this->value = $hasher;

      return $this;
   }


   public static function fromPlainUnhashed(string $plainPassword): self
   {
      $plainPassword = trim($plainPassword);

      if ($plainPassword === '') {
         throw new ValueObjectInvalidException('Password cannot be empty.');
      }

      return new self($plainPassword);
   }

   public static function fromHash(string $hash): self
   {
      return new self($hash);
   }

   public static function fromPlain(string $plainPassword): self
   {
      return new self($plainPassword);
   }


   public function verify(
      string $plainPassword,
      PasswordHasher $hasher
   ): bool {
      return $hasher->verify($this->value, $plainPassword);
   }

   public function needsRehash(PasswordHasher $hasher): bool
   {
      return $hasher->needsRehash($this->value);
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
      return hash_equals($this->value, $other->value);
   }
}
