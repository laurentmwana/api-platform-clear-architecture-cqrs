<?php

namespace App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\IdentityAndAccess\Application\Command\LogoutCommand;
use App\IdentityAndAccess\Infrastructure\Framework\Security\SecurityUser;
use App\IdentityAndAccess\Presentation\Output\LogoutOutput;
use App\SharedContext\Application\Bus\Command\CommandBus;
use App\SharedContext\Domain\ValueObject\IpAddress;
use App\SharedContext\Domain\ValueObject\JwtToken;
use App\SharedContext\Domain\ValueObject\UserAgent;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\Token\JWTPostAuthenticationToken;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * @implements ProcessorInterface<null, LogoutOutput>
 */
class LogoutProcessor implements ProcessorInterface
{
   public function __construct(
      private CommandBus $commandBus,
      private Security $security,
   ) {}

   public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): LogoutOutput
   {
      /** @var Request|null $request */
      $request = $context['request'] ?? null;

      if (!$request) {
         throw new \RuntimeException('Missing request in context.');
      }

      $ip = $request->getClientIp();
      $userAgent = $request->headers->get('User-Agent');

      /** @var SecurityUser $securityUser */

      $securityUser = $this->security->getUser();

      /** @var JWTPostAuthenticationToken $jwtToken */
      $jwtToken = $this->security->getToken();

      $command = new LogoutCommand(
         $securityUser->getUser(),
         new JwtToken($jwtToken->getCredentials()),
         $ip ? new IpAddress($ip) : null,
         $userAgent ? new UserAgent($userAgent) : null,
      );

      $this->commandBus->dispatch($command);

      return new LogoutOutput("Logged out successfully");
   }
}
