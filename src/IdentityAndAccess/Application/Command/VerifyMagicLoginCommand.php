<?php

namespace App\IdentityAndAccess\Application\Command;

use App\IdentityAndAccess\Domain\ValueObject\EmailOrPhone;
use App\IdentityAndAccess\Domain\ValueObject\OtpCode;
use App\SharedContext\Domain\ValueObject\IpAddress;
use App\SharedContext\Domain\ValueObject\UserAgent;

class VerifyMagicLoginCommand
{
   public function __construct(
      private OtpCode $otpCode,
      private EmailOrPhone $identifier,
      private ?IpAddress $ipAddress = null,
      private ?UserAgent $userAgent = null,
   ) {}

   public function getOtpCode(): OtpCode
   {
      return $this->otpCode;
   }

   public function getIdentifier(): EmailOrPhone
   {
      return $this->identifier;
   }

   public function getIpAddress(): ?IpAddress
   {
      return $this->ipAddress;
   }

   public function getUserAgent(): ?UserAgent
   {
      return $this->userAgent;
   }
}
