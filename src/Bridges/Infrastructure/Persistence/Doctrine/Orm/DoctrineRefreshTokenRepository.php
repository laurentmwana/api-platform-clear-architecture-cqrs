<?php

namespace App\Bridges\Infrastructure\Persistence\Doctrine\Orm;

use App\Bridges\Infrastructure\Persistence\Doctrine\Entity\RefreshToken;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Gesdinet\JWTRefreshTokenBundle\Doctrine\RefreshTokenRepositoryInterface;

/**
 * @extends ServiceEntityRepository<RefreshToken>
 * @implements RefreshTokenRepositoryInterface<RefreshToken>
 */
class DoctrineRefreshTokenRepository extends ServiceEntityRepository implements RefreshTokenRepositoryInterface
{
   public function __construct(ManagerRegistry $registry)
   {
      parent::__construct($registry, RefreshToken::class);
   }

   /**
    * @inheritDoc
    */
   public function findInvalid(?DateTimeInterface $datetime = null): iterable
   {
      $datetime = $datetime ?? new \DateTime();

      return $this->createQueryBuilder('rt')
         ->andWhere('rt.valid < :datetime')
         ->setParameter('datetime', $datetime)
         ->getQuery()
         ->toIterable();
   }

   /**
    * @inheritDoc
    */
   public function findInvalidBatch(?DateTimeInterface $datetime = null, ?int $batchSize = null, int $offset = 0): iterable
   {
      $datetime = $datetime ?? new \DateTime();

      $qb = $this->createQueryBuilder('rt')
         ->andWhere('rt.valid < :datetime')
         ->setParameter('datetime', $datetime);

      if ($batchSize !== null) {
         $qb->setMaxResults($batchSize);
      }

      $qb->setFirstResult($offset);

      return $qb->getQuery()->toIterable();
   }
}
