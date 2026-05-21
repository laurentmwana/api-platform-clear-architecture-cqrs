<?php

namespace App\IdentityAndAccess\Infrastructure\Framework\Security;

use App\IdentityAndAccess\Domain\Entity\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class SecurityUser implements
   UserInterface,
   PasswordAuthenticatedUserInterface
{
   private function __construct(private User $user) {}

   public static function create(User $user): self
   {
      return new self($user);
   }

   public function getUser(): User
   {
      return $this->user;
   }

   public function getPassword(): string
   {
      return $this->user->getPassword()->value();
   }

   /**
    * @return array<int, string>
    */
   public function getRoles(): array
   {
      return $this->user->getRoles()->toArray();
   }

   #[\Override]
   public function getUserIdentifier(): string
   {
      return $this->user->getEmail()->value();
   }

   public function getId(): string
   {
      return $this->user->getId()->value();
   }
}
