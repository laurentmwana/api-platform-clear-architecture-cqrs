<?php

namespace App\SharedContext\Domain\Service;

interface TokenGenerator
{
   public function alphaNumeric(int $length = 50): string;
}
