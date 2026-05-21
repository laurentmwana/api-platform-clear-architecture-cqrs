<?php

namespace App\IdentityAndAccess\Domain\Exception;

class UserCredentialsException extends AuthenticationException
{
   public function __construct(string $message = 'Invalid credentials. Please check your identifier and password.')
   {
      parent::__construct($message);
   }
}
