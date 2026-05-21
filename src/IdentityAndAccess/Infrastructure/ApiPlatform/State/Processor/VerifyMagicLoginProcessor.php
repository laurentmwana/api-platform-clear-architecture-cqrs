<?php

namespace App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\IdentityAndAccess\Application\Command\VerifyMagicLoginCommand;
use App\IdentityAndAccess\Domain\ValueObject\EmailOrPhone;
use App\IdentityAndAccess\Domain\ValueObject\OtpCode;
use App\IdentityAndAccess\Presentation\Input\VerifyOtpInput;
use App\IdentityAndAccess\Presentation\Output\JwtTokenOutput;
use App\SharedContext\Application\Bus\Command\CommandBus;
use App\SharedContext\Domain\Service\RateLimiter;
use App\SharedContext\Domain\ValueObject\IpAddress;
use App\SharedContext\Domain\ValueObject\UserAgent;
use Symfony\Component\HttpFoundation\Request;

/**
 * @implements ProcessorInterface<VerifyOtpInput, JwtTokenOutput>
 */
class VerifyMagicLoginProcessor implements ProcessorInterface
{
   public function __construct(
      private CommandBus $commandBus,
      private RateLimiter $rateLimiter
   ) {}

   public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
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

      $userAgent = $request->headers->get('User-Agent');

      $command = new VerifyMagicLoginCommand(
         new OtpCode($data->getCode()),
         EmailOrPhone::fromString($data->getIdentifier()),
         new IpAddress($ip),
         $userAgent ? new UserAgent($userAgent) : null,
      );

      $tokens = $this->commandBus->dispatch($command);

      return new JwtTokenOutput($tokens);
   }
}
