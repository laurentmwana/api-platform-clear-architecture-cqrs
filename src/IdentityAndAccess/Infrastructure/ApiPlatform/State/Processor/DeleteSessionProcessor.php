<?php

namespace App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\IdentityAndAccess\Application\Command\DeleteSessionCommand;
use App\IdentityAndAccess\Domain\ValueObject\Password;
use App\IdentityAndAccess\Infrastructure\Framework\Security\SecurityUser;
use App\SharedContext\Application\Bus\Command\CommandBus;
use App\SharedContext\Domain\ValueObject\Uuid;
use App\SharedContext\Presentation\Input\CurrentPasswordInput;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @implements ProcessorInterface<CurrentPasswordInput, void>
 */
class DeleteSessionProcessor implements ProcessorInterface
{
   public function __construct(
      private CommandBus $commandBus,
      private Security $security,
   ) {}

   public function process(
      mixed $data,
      Operation $operation,
      array $uriVariables = [],
      array $context = []
   ): void {

      /** @var Request|null $request */
      $request = $context['request'] ?? null;

      if (!$request) {
         throw new \RuntimeException('Missing request in context.');
      }

      /** @var SecurityUser $securityUser */
      $securityUser = $this->security->getUser();

      $user = $securityUser->getUser();

      $sessionId = $uriVariables['id'] ?? null;

      if (!$sessionId) {
         throw new NotFoundHttpException('Session ID is required.');
      }

      $password = Password::fromPlain($data->getCurrentPassword());

      $command = new DeleteSessionCommand($user, $password, new Uuid($sessionId));

      $this->commandBus->dispatch($command);
   }
}
