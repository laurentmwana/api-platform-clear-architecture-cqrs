<?php

namespace App\SharedContext\Domain\Service;

use App\SharedContext\Domain\ValueObject\Uuid;

interface UuidGenerator
{
   public function generate(): Uuid;
}
