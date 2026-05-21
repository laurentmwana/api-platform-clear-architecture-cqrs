<?php

namespace App\SharedContext\Application\Bus\Message;

use App\SharedContext\Domain\Enums\DeliveryChannelEnum;
use App\SharedContext\Domain\ValueObject\Message;

interface MessageDispatcher
{
   public function send(Message $message, DeliveryChannelEnum $channel): void;
}
