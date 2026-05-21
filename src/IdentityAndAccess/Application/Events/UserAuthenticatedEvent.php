<?php

namespace App\IdentityAndAccess\Application\Events;

use App\SharedContext\Domain\ValueObject\IpAddress;
use App\SharedContext\Domain\ValueObject\UserAgent;
use App\SharedContext\Domain\ValueObject\Uuid;

class UserAuthenticatedEvent
{
   public function __construct(
      private Uuid $userId,
      private ?IpAddress $ipAddress = null,
      private ?UserAgent $userAgent = null,
   ) {}

   public function getUserId(): Uuid
   {
      return $this->userId;
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
