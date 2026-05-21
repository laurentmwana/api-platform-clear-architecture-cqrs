<?php

namespace App\SharedContext\Application\Command;

use App\SharedContext\Domain\ValueObject\Message;
use App\SharedContext\Domain\Enums\DeliveryChannelEnum;

class SendMessageCommand
{
   public function __construct(
      private Message $message,
      private DeliveryChannelEnum $channel,
   ) {}

   public function getMessage(): Message
   {
      return $this->message;
   }

   public function getChannel(): DeliveryChannelEnum
   {
      return $this->channel;
   }
}
