<?php

namespace App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\IdentityAndAccess\Application\Command\VerifyPhoneCommand;
use App\IdentityAndAccess\Domain\Enums\OtpTypeEnum;
use App\IdentityAndAccess\Domain\ValueObject\OtpCode;
use App\IdentityAndAccess\Infrastructure\Framework\Security\SecurityUser;
use App\IdentityAndAccess\Presentation\Input\VerifyEmailOrPhoneInput;
use App\IdentityAndAccess\Presentation\Output\VerifyOtpCodeOutput;
use App\SharedContext\Application\Bus\Command\CommandBus;
use App\SharedContext\Domain\Service\RateLimiter;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * @implements ProcessorInterface<VerifyEmailOrPhoneInput, array{message:string}>
 */
class VerifyPhoneProcessor implements ProcessorInterface
{
   public function __construct(
      private CommandBus $commandBus,
      private RateLimiter $rateLimiter,
      private Security $security,
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

      /** @var SecurityUser $securityUser */

      $securityUser = $this->security->getUser();

      $user = $securityUser->getUser();

      $code =  new OtpCode($data->getOtpCode());

      $command = new VerifyPhoneCommand($user, $code);

      /** @var OtpTypeEnum $type */

      $type = $this->commandBus->dispatch($command);

      return VerifyOtpCodeOutput::toArray($type);
   }
}
