<?php

namespace App\SharedContext\Domain\ValueObject;

use App\SharedContext\Domain\Exception\ValueObjectInvalidException;
use Stringable;

final class IpAddress implements Stringable
{
   public function __construct(
      private readonly string $value
   ) {
      if (!filter_var($value, FILTER_VALIDATE_IP)) {
         throw new ValueObjectInvalidException('Invalid IP address');
      }
   }

   public function value(): string
   {
      return $this->value;
   }

   public function __toString(): string
   {
      return $this->value;
   }
}
