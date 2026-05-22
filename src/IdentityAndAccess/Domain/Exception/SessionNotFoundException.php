<?php

namespace App\IdentityAndAccess\Domain\Exception;

use App\SharedContext\Domain\Exception\EntityNotFoundException;

class SessionNotFoundException extends EntityNotFoundException
{
   public function __construct(string $message = "Session Not found")
   {
      parent::__construct($message);
   }
}
