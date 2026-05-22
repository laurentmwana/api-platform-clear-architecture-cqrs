<?php

namespace App\SharedContext\Domain\Exception;

class PasswordIncorrectException extends UnprocessableException
{
   public function __construct(string $message = 'Current password is incorrect')
   {
      parent::__construct($message);
   }
}
