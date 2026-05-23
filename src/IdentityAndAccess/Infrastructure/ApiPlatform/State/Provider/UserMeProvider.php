<?php

namespace App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\IdentityAndAccess\Infrastructure\Framework\Security\SecurityUser;
use App\IdentityAndAccess\Presentation\Output\UserMeOutput;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<UserMeOutput>
 */
final class UserMeProvider implements ProviderInterface
{
   public function __construct(
      private Security $security,
   ) {}


   /**
    * @inheritDoc
    * @return UserMeOutput
    */
   public function provide(Operation $operation, array $uriVariables = [], array $context = []): UserMeOutput
   {
      $securityUser = $this->security->getUser();

      if (!$securityUser instanceof SecurityUser) {
         throw new \RuntimeException('Missing authenticated user.');
      }

      $user = $securityUser->getUser();

      return UserMeOutput::fromUser($user);
   }
}
