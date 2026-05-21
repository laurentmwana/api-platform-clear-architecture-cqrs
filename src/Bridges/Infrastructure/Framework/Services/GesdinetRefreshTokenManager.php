<?php

namespace App\Bridges\Infrastructure\Framework\Services;

use App\Bridges\Domain\Services\RefreshTokenManager;
use App\IdentityAndAccess\Domain\Entity\User;
use App\IdentityAndAccess\Infrastructure\Framework\Security\SecurityUser;
use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;

class GesdinetRefreshTokenManager implements RefreshTokenManager
{
   public function __construct(
      private RefreshTokenGeneratorInterface $refreshTokenGenerator,
      private RefreshTokenManagerInterface $refreshTokenManager,
   ) {}

   public function generate(User $user, int $ttl = 2592000): string
   {
      $securityUser = SecurityUser::create($user);

      $refreshToken = $this->refreshTokenGenerator
         ->createForUserWithTtl($securityUser, $ttl);

      $this->refreshTokenManager->save($refreshToken);

      return (string) $refreshToken->getRefreshToken();
   }
}
