<?php

namespace App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\IdentityAndAccess\Application\Query\GetUserSessionsQuery;
use App\IdentityAndAccess\Domain\Entity\Session;
use App\IdentityAndAccess\Infrastructure\Framework\Security\SecurityUser;
use App\IdentityAndAccess\Presentation\Output\SessionOutput;
use App\SharedContext\Application\Bus\Query\QueryBus;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<SessionOutput>
 */
final class SessionsProvider implements ProviderInterface
{
   public function __construct(
      private QueryBus $queryBus,
      private Security $security,
   ) {}


   /**
    * @inheritDoc
    * @return SessionOutput[]
    */
   public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
   {
      $securityUser = $this->security->getUser();

      if (!$securityUser instanceof SecurityUser) {
         throw new \RuntimeException('Missing authenticated user.');
      }

      $user = $securityUser->getUser();

      $sessions = $this->queryBus->dispatch(
         new GetUserSessionsQuery($user->getId())
      );

      return  array_map(
         fn(Session $session) => $this->toOutput($session),
         $sessions
      );
   }

   private function toOutput(Session $session): SessionOutput
   {
      $output = new SessionOutput(
         id: (string) $session->getId(),
         userAgent: $session->getUserAgent()?->value(),
         ipAddress: $session->getIpAddress()?->value(),
         createdAt: $session->getCreatedAt()->format(DATE_ATOM)
      );

      return $output;
   }
}
