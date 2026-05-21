<?php

namespace App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\IdentityAndAccess\Application\Command\MagicLoginCommand;
use App\IdentityAndAccess\Domain\ValueObject\EmailOrPhone;
use App\IdentityAndAccess\Presentation\Input\IdentifierInput;
use App\IdentityAndAccess\Presentation\Output\SendOtpCodeOutput;
use App\IdentityAndAccess\Domain\Enums\OtpTypeEnum;
use App\SharedContext\Application\Bus\Command\CommandBus;
use App\SharedContext\Domain\Service\RateLimiter;
use Symfony\Component\HttpFoundation\Request;

/**
 * @implements ProcessorInterface<IdentifierInput, array<string,mixed>>
 */
class MagicLoginProcessor implements ProcessorInterface
{
   public function __construct(
      private CommandBus $commandBus,
      private RateLimiter $rateLimiter
   ) {}

   public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): array
   {
      /** @var Request|null $request */
      $request = $context['request'] ?? null;

      if (!$request) {
         throw new \RuntimeException('Missing request in context.');
      }

      $ip = $request->getClientIp();

      if (!$ip) {
         throw new \RuntimeException('Cannot resolve client IP.');
      }

      $this->rateLimiter->throttle($ip);

      $command = new MagicLoginCommand(
         EmailOrPhone::fromString($data->getIdentifier())
      );

      /** @var OtpTypeEnum $type */

      $type = $this->commandBus->dispatch($command);

      return SendOtpCodeOutput::toArray($type);
   }
}
