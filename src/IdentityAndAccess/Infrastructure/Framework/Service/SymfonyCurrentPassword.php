<?php

namespace App\IdentityAndAccess\Infrastructure\Framework\Service;

use App\IdentityAndAccess\Domain\Service\PasswordHasher;
use App\IdentityAndAccess\Domain\ValueObject\Password;
use App\SharedContext\Domain\Exception\PasswordIncorrectException;
use App\IdentityAndAccess\Domain\Service\CurrentPassword;

class SymfonyCurrentPassword implements CurrentPassword
{
   public function __construct(
      private PasswordHasher $hasher
   ) {}

   public function confirm(Password $hashPassword, Password $plainPassword): void
   {
      $isMatch = $this->hasher->verify(
         (string) $hashPassword,
         (string) $plainPassword
      );

      if (!$isMatch) {
         throw new PasswordIncorrectException();
      }
   }
}
