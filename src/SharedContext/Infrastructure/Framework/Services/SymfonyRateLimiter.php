<?php

namespace App\SharedContext\Infrastructure\Framework\Services;

use App\SharedContext\Domain\Enums\RateLimiterKeysEnum;
use App\SharedContext\Domain\Service\RateLimiter;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class SymfonyRateLimiter implements RateLimiter
{
   public function __construct(private ContainerInterface $limiterLocator) {}

   public function throttle(string $key, RateLimiterKeysEnum $type = RateLimiterKeysEnum::DEFAULT): void
   {
      $this->limit($key, $type->value);
   }

   private function limit(string $key, string $configName): void
   {
      if (!$this->limiterLocator->has($configName)) {
         throw new \RuntimeException("Limiter configuration '$configName' not found.");
      }

      $factory = $this->limiterLocator->get($configName);
      $limiter = $factory->create($key);

      if (!$limiter->consume(1)->isAccepted()) {
         throw new TooManyRequestsHttpException(
            message: 'Too many attempts. Please try again later.'
         );
      }
   }
}
