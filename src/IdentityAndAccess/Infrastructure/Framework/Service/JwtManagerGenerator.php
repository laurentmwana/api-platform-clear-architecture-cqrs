<?php

namespace App\IdentityAndAccess\Infrastructure\Framework\Service;

use App\IdentityAndAccess\Domain\Entity\User;
use App\IdentityAndAccess\Domain\Service\JwtTokenGenerator;
use App\IdentityAndAccess\Infrastructure\Framework\Security\SecurityUser;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class JwtManagerGenerator implements JwtTokenGenerator
{
   public function __construct(
      private JWTTokenManagerInterface $jwt,
   ) {}

   public function generate(User $user): string
   {
      return $this->jwt->create(SecurityUser::create($user));
   }
}
