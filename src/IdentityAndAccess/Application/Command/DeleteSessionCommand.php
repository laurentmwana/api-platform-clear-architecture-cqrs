<?php

namespace App\IdentityAndAccess\Application\Command;

use App\IdentityAndAccess\Domain\Entity\User;
use App\IdentityAndAccess\Domain\ValueObject\Password;
use App\SharedContext\Domain\ValueObject\Uuid;

final class DeleteSessionCommand
{
   public function __construct(
      private User $user,
      private Password $password,
      private Uuid $sessionId,
   ) {}

   public function getUser(): User
   {
      return $this->user;
   }

   public function getSessionId(): Uuid
   {
      return $this->sessionId;
   }

   public function getPassword(): Password
   {
      return $this->password;
   }
}
