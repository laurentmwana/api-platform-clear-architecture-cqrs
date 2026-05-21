<?php

namespace App\IdentityAndAccess\Application\Command;

use App\IdentityAndAccess\Domain\ValueObject\EmailOrPhone;

final class ForgotPasswordCommand
{
   public function __construct(private EmailOrPhone $identifier) {}

   public function getIdentifier(): EmailOrPhone
   {
      return $this->identifier;
   }
}
