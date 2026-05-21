<?php

namespace App\IdentityAndAccess\Infrastructure\Framework\Security;

use App\SharedContext\Domain\ValueObject\Email;
use App\IdentityAndAccess\Domain\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @implements UserProviderInterface<SecurityUser>
 */
final readonly class SecurityUserProvider implements UserProviderInterface
{
   public function __construct(
      private UserRepository $userRepository
   ) {}

   #[\Override]
   public function refreshUser(UserInterface $user): UserInterface
   {
      return $this->loadUserByIdentifier($user->getUserIdentifier());
   }

   #[\Override]
   public function loadUserByIdentifier(string $identifier): UserInterface
   {
      $user = $this->userRepository->findByEmail(new Email($identifier));

      if ($user === null) {
         throw new UserNotFoundException();
      }

      return SecurityUser::create($user);
   }

   #[\Override]
   public function supportsClass(string $class): bool
   {
      return $class === SecurityUser::class;
   }
}
