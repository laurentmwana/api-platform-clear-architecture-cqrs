<?php

namespace App\IdentityAndAccess\Domain\Exception;

class InvalidIdentifierException extends AuthenticationException
{
   public function __construct(string $identifier)
   {
      $message = sprintf(
         'Invalid identifier "%s". Must be a valid email address or phone number (+243XXXXXXXXX).',
         $identifier
      );

      parent::__construct($message);
   }
}
