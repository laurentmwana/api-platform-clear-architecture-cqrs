<?php

namespace App\IdentityAndAccess\Application\Command;

use App\IdentityAndAccess\Domain\Entity\User;

class SendVerificationEmailCommand
{
   public function __construct(private User $user) {}

   public function getUser(): User
   {
      return $this->user;
   }
}
