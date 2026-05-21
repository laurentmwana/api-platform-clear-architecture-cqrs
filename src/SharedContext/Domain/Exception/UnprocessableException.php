<?php

namespace App\SharedContext\Domain\Exception;

use DomainException;

class UnprocessableException extends DomainException
{
   public function __construct(
      string $message = 'The request cannot be processed.',
      int $code = 422,
      ?\Throwable $previous = null,
   ) {
      parent::__construct($message, $code, $previous);
   }
}
