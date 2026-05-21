<?php

namespace App\IdentityAndAccess\Domain\Repository;

use App\SharedContext\Domain\ValueObject\Email;
use App\IdentityAndAccess\Domain\Entity\User;
use App\IdentityAndAccess\Domain\ValueObject\EmailOrPhone;
use App\SharedContext\Domain\Repository\RepositoryInterface;

interface UserRepository extends RepositoryInterface
{
   public function findByEmail(Email $email): ?User;
   public function findByIdentifier(EmailOrPhone $identifier): ?User;
}
