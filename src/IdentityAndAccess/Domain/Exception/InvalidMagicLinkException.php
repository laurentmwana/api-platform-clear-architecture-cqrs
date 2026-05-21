<?php

namespace App\IdentityAndAccess\Domain\Exception;

class InvalidMagicLinkException extends AuthenticationException
{
   public function __construct()
   {
      parent::__construct('Invalid or expired magic link.');
   }
}
