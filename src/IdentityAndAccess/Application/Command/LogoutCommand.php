<?php

namespace App\IdentityAndAccess\Application\Command;

use App\IdentityAndAccess\Domain\Entity\User;
use App\SharedContext\Domain\ValueObject\IpAddress;
use App\SharedContext\Domain\ValueObject\JwtToken;
use App\SharedContext\Domain\ValueObject\UserAgent;

class LogoutCommand
{
   public function __construct(
      private User $user,
      private JwtToken $jwtToken,
      private ?IpAddress $ipAddress = null,
      private ?UserAgent $userAgent = null,
   ) {}

   public function getUserAgent(): ?UserAgent
   {
      return $this->userAgent;
   }

   public function getIpAddress(): ?IpAddress
   {
      return $this->ipAddress;
   }

   public function getUser(): User
   {
      return $this->user;
   }

   public function getJwtToken(): JwtToken
   {
      return $this->jwtToken;
   }
}
