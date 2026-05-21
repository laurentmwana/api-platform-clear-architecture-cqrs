<?php

namespace App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\IdentityAndAccess\Application\Command\ResetPasswordCommand;
use App\IdentityAndAccess\Domain\ValueObject\EmailOrPhone;
use App\IdentityAndAccess\Domain\ValueObject\OtpCode;
use App\IdentityAndAccess\Domain\ValueObject\Password;
use App\IdentityAndAccess\Presentation\Input\ResetPasswordInput;
use App\IdentityAndAccess\Presentation\Output\ResetPasswordOutput;
use App\SharedContext\Application\Bus\Command\CommandBus;
use Symfony\Component\HttpFoundation\Request;

/**
 * @implements ProcessorInterface<ResetPasswordInput, array{message:string}>
 */
class ResetPasswordProcessor implements ProcessorInterface
{
   public function __construct(private CommandBus $commandBus) {}

   public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): array
   {
      /** @var Request|null $request */
      $request = $context['request'] ?? null;

      if (!$request) {
         throw new \RuntimeException('Missing request in context.');
      }

      $command = new ResetPasswordCommand(
         EmailOrPhone::fromString($data->getIdentifier()),
         new OtpCode($data->getOtpCode()),
         Password::fromPlain($data->getNewPassword()),
      );

      $this->commandBus->dispatch($command);

      return ResetPasswordOutput::toArray();
   }
}
