<?php

namespace App\SharedContext\Infrastructure\Persistence\Doctrine\Orm;

trait DoctrineRepositoryTrait
{
   public function save(object $entity): void
   {
      $this->getEntityManager()->persist($entity);
      $this->getEntityManager()->flush();
   }

   public function remove(object $entity): void
   {
      $this->getEntityManager()->remove($entity);
      $this->getEntityManager()->flush();
   }
}
