<?php

namespace App\IdentityAndAccess\Domain\Service;

use App\SharedContext\Domain\ValueObject\JwtToken;

interface AccessTokenManager
{
   public function invalidate(JwtToken $token): bool;
}
