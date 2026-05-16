<?php

namespace App\IdentityAndAccess\Infrastructure\Framework\Service;

use App\IdentityAndAccess\Domain\Service\AccessTokenManager;
use App\SharedContext\Domain\ValueObject\JwtToken;
use Lexik\Bundle\JWTAuthenticationBundle\Services\BlockedTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

final class JwtAccessTokenManager implements AccessTokenManager
{
   public function __construct(
      private readonly JWTTokenManagerInterface $jwtManager,
      private readonly BlockedTokenManagerInterface $blockedTokenManager,
   ) {}

   public function invalidate(JwtToken $token): bool
   {
      try {
         $payload = $this->jwtManager->parse($token->value());

         $this->blockedTokenManager->add($payload);

         return true;
      } catch (\Throwable) {
         return false;
      }
   }
}
