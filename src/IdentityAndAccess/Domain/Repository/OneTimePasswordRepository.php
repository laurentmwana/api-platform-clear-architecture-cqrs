<?php

namespace App\IdentityAndAccess\Domain\Repository;

use App\IdentityAndAccess\Domain\Entity\OneTimePassword;
use App\IdentityAndAccess\Domain\ValueObject\OtpType;
use App\SharedContext\Domain\Repository\RepositoryInterface;
use App\SharedContext\Domain\ValueObject\Uuid;

interface OneTimePasswordRepository extends RepositoryInterface
{
   public function findValidByUserId(Uuid $userId, OtpType $type): ?OneTimePassword;
}
