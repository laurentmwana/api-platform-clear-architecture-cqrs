<?php

namespace App\SharedContext\Domain\Repository;

namespace App\SharedContext\Domain\Repository;

interface RepositoryInterface
{
   public function save(object $entity): void;
   public function remove(object $entity): void;
}
