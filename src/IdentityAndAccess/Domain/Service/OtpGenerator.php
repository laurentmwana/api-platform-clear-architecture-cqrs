<?php

namespace App\IdentityAndAccess\Domain\Service;

use App\IdentityAndAccess\Domain\Entity\OneTimePassword;
use App\IdentityAndAccess\Domain\Entity\User;
use App\IdentityAndAccess\Domain\ValueObject\DeliveryChannel;
use App\IdentityAndAccess\Domain\ValueObject\OtpCode;
use App\IdentityAndAccess\Domain\ValueObject\OtpType;
use App\SharedContext\Domain\ValueObject\Uuid;

interface OtpGenerator
{
   public function generate(
      User $user,
      OtpType $type,
      DeliveryChannel $method
   ): OneTimePassword;

   public function consume(Uuid $userId, OtpType $type,  OtpCode $code): bool;
}
