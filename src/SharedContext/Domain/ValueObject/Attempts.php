<?php

namespace App\SharedContext\Domain\ValueObject;

use App\SharedContext\Domain\Exception\ValueObjectInvalidException;
use Stringable;

final class Attempts implements Stringable
{
   public function __construct(
      private readonly int $value,
      private readonly int $max = 5
   ) {
      if ($value < 0) {
         throw new ValueObjectInvalidException(
            'Attempts must be >= 0'
         );
      }

      if ($value > $this->max) {
         throw new ValueObjectInvalidException(
            "Attempts cannot exceed {$this->max}"
         );
      }
   }

   public function minus(): self
   {
      return new self($this->value - 1, $this->max);
   }

   public function value(): int
   {
      return $this->value;
   }

   public function __toString(): string
   {
      return  (string)$this->value;
   }
}
