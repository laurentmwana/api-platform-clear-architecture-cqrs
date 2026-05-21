<?php

namespace App\SharedContext\Infrastructure\Framework\Services;

use App\SharedContext\Domain\Service\UuidGenerator;
use App\SharedContext\Domain\ValueObject\Uuid;
use Symfony\Component\Uid\Uuid as SymfonyUuid;

class SymfonyUuidGenerator implements UuidGenerator
{
   public function generate(): Uuid
   {
      return new Uuid(SymfonyUuid::v7()->toString());
   }
}
