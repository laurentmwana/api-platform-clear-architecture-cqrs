<?php

namespace App\IdentityAndAccess\Application\Query;

use App\SharedContext\Domain\ValueObject\Uuid;

class GetUserSessionsQuery
{
   public function __construct(
      private Uuid $userId
   ) {}

   public function getUserId(): Uuid
   {
      return $this->userId;
   }
}
