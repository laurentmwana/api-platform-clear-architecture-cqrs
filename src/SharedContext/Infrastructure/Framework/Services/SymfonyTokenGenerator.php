<?php

namespace App\SharedContext\Infrastructure\Framework\Services;

use App\SharedContext\Domain\Service\TokenGenerator;
use Symfony\Component\String\ByteString;


class SymfonyTokenGenerator implements TokenGenerator
{
   public function alphaNumeric(int $length = 50): string
   {
      return ByteString::fromRandom($length);
   }
}
