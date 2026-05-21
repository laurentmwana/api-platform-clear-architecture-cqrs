<?php

namespace App\IdentityAndAccess\Infrastructure\Persistence\Doctrine\Orm;

use App\IdentityAndAccess\Domain\Entity\User;
use App\IdentityAndAccess\Domain\Repository\UserRepository;
use App\IdentityAndAccess\Domain\ValueObject\EmailOrPhone;
use App\SharedContext\Domain\ValueObject\Email;
use App\SharedContext\Infrastructure\Persistence\Doctrine\Orm\DoctrineRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class DoctrineUserRepository extends ServiceEntityRepository implements UserRepository
{
   use DoctrineRepositoryTrait;

   public function __construct(ManagerRegistry $registry)
   {
      parent::__construct($registry, User::class);
   }

   public function findByEmail(Email $email): ?User
   {
      return $this->findOneBy([
         'email' => $email->value()
      ]);
   }

   public function findByIdentifier(EmailOrPhone $identifier): ?User
   {
      $value = $identifier->value();

      return $this->createQueryBuilder('u')
         ->where('u.email = :value OR u.phone = :value')
         ->setParameter('value', $value)
         ->getQuery()
         ->getOneOrNullResult();
   }
}
