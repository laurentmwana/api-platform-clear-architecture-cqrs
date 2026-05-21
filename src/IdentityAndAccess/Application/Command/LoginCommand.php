<?php

namespace App\IdentityAndAccess\Application\Command;

use App\IdentityAndAccess\Domain\ValueObject\EmailOrPhone;
use App\IdentityAndAccess\Domain\ValueObject\Password;
use App\SharedContext\Domain\ValueObject\IpAddress;
use App\SharedContext\Domain\ValueObject\UserAgent;

final class LoginCommand
{
   public function __construct(
      private EmailOrPhone $identifier,
      private Password $password,
      private ?IpAddress $ipAddress = null,
      private ?UserAgent $userAgent = null
   ) {}

   public function getPassword(): Password
   {
      return $this->password;
   }

   public function getIpAddress(): ?IpAddress
   {
      return $this->ipAddress;
   }

   public function getUserAgent(): ?UserAgent
   {
      return $this->userAgent;
   }

   public function getIdentifier(): EmailOrPhone
   {
      return $this->identifier;
   }
}
