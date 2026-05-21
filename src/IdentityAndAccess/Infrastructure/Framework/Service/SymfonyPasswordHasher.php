<?php

namespace App\IdentityAndAccess\Infrastructure\Framework\Service;

use App\IdentityAndAccess\Domain\Entity\User;
use App\IdentityAndAccess\Domain\Service\PasswordHasher;
use App\IdentityAndAccess\Domain\ValueObject\Password;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

final class SymfonyPasswordHasher implements PasswordHasher
{
   private PasswordHasherInterface $hasher;

   public function __construct(PasswordHasherFactoryInterface $factory)
   {
      $this->hasher = $factory->getPasswordHasher(User::class);
   }

   public function hash(Password $plainPassword): Password
   {
      $hasher =  $this->hasher->hash($plainPassword->value());

      return $plainPassword->changeValue($hasher);
   }

   public function verify(string $hashedPassword, string $plainPassword): bool
   {
      return $this->hasher->verify($hashedPassword, $plainPassword);
   }

   public function needsRehash(string $hashedPassword): bool
   {
      return $this->hasher->needsRehash($hashedPassword);
   }
}
