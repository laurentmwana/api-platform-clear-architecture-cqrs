<?php

namespace App\IdentityAndAccess\Infrastructure\Persistence\Doctrine\Orm;

use App\IdentityAndAccess\Domain\Entity\OneTimePassword;
use App\IdentityAndAccess\Domain\Repository\OneTimePasswordRepository;
use App\IdentityAndAccess\Domain\ValueObject\OtpType;
use App\SharedContext\Domain\ValueObject\Uuid;
use App\SharedContext\Infrastructure\Persistence\Doctrine\Orm\DoctrineRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OneTimePassword>
 */
class DoctrineOneTimePasswordRepository extends ServiceEntityRepository implements OneTimePasswordRepository
{
   use DoctrineRepositoryTrait;

   public function __construct(ManagerRegistry $registry)
   {
      parent::__construct($registry, OneTimePassword::class);
   }

   public function findValidByUserId(Uuid $userId, OtpType $type): ?OneTimePassword
   {
      return  $this->createQueryBuilder('o')
         ->where('o.userId = :userId')
         ->andWhere('o.type = :type')
         ->setParameter('userId', $userId->value())
         ->setParameter('type', $type->value())
         ->getQuery()
         ->getOneOrNullResult();
   }
}
