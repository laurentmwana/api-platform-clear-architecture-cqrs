<?php

namespace App\SharedContext\Domain\Service;

use App\SharedContext\Domain\Enums\RateLimiterKeysEnum;

interface RateLimiter
{
   public function throttle(string $key, RateLimiterKeysEnum $type = RateLimiterKeysEnum::DEFAULT): void;
}
