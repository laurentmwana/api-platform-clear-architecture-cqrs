<?php

namespace App\SharedContext\Domain\ValueObject;

use App\SharedContext\Domain\Exception\ValueObjectInvalidException;

final class EmailTemplate
{
   private function __construct(private string $value)
   {
      $value = trim($value);

      if ($value === '') {
         throw new ValueObjectInvalidException('Template name cannot be empty.');
      }
   }

   public static function magicLink(): self
   {
      return new self('emails/magic_link.html.twig');
   }


   public function value(): string
   {
      return $this->value;
   }
}
