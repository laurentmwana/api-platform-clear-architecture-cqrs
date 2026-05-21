<?php

namespace App\Bridges\Domain\Services;

use App\IdentityAndAccess\Domain\Entity\User;

interface RefreshTokenManager
{
   public function generate(User $user, int $ttl = 2592000): string;
}
