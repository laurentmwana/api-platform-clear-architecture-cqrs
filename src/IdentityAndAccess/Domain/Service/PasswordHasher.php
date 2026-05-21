<?php

namespace App\IdentityAndAccess\Domain\Service;

use App\IdentityAndAccess\Domain\ValueObject\Password;

interface PasswordHasher
{
   public function hash(Password $password): Password;

   public function verify(string $hashedPassword, string $plainPassword): bool;

   public function needsRehash(string $hashedPassword): bool;
}
