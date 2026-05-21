<?php

namespace App\IdentityAndAccess\Domain\Service;

use App\IdentityAndAccess\Domain\Entity\User;
use App\SharedContext\Domain\ValueObject\JwtToken;

interface AccessTokenManager
{
   public function generate(User $user): string;

   public function invalidate(JwtToken $token): bool;
}
