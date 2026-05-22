<?php

namespace App\IdentityAndAccess\Domain\Repository;

use App\IdentityAndAccess\Domain\Entity\Session;
use App\SharedContext\Domain\Repository\RepositoryInterface;
use App\SharedContext\Domain\ValueObject\IpAddress;
use App\SharedContext\Domain\ValueObject\UserAgent;
use App\SharedContext\Domain\ValueObject\Uuid;

interface SessionRepository extends RepositoryInterface
{
   public function findOneByUserId(Uuid $userId): ?Session;

   /**
    * @return array<int, Session>
    */
   public function findAllByUserId(Uuid $userId): array;

   public function findOneByUserIdAndDevice(
      Uuid $userId,
      IpAddress $ipAddress,
      UserAgent $userAgent
   ): ?Session;

   public function findOneByIdAndUserId(Uuid $sessionId, Uuid $userId): ?Session;
}
