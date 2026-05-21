<?php

namespace App\IdentityAndAccess\Domain\Exception;

use Exception;

class AuthenticationException extends Exception
{
   public function __construct(string $message = "authentification failed")
   {
      parent::__construct($message);
   }
}
