<?php

namespace App\IdentityAndAccess\Domain\Repository;

use App\IdentityAndAccess\Domain\Entity\Session;
use App\SharedContext\Domain\Repository\RepositoryInterface;
use App\SharedContext\Domain\ValueObject\IpAddress;
use App\SharedContext\Domain\ValueObject\UserAgent;
use App\SharedContext\Domain\ValueObject\Uuid;

interface SessionRepository extends RepositoryInterface
{
   /**
    * @param Uuid $userId
    * @return Session|null
    */
   public function findByUserId(Uuid $userId): ?Session;

   /**
    * @param Uuid $userId
    * @return array<int, Session>
    */
   public function findAllByUserId(Uuid $userId): array;

   public function findByUserIdAndDevice(Uuid $userId, IpAddress $ipAddress, UserAgent $userAgent): ?Session;
}
