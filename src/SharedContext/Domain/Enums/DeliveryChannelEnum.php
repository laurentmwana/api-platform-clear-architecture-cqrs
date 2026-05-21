<?php

namespace App\SharedContext\Domain\Enums;

use App\SharedContext\Domain\Traits\Enumerable;

enum DeliveryChannelEnum: string
{
   use Enumerable;

   case SMS = 'sms';
   case EMAIL = 'mail';
   case WHATSAPP = "whatsapp";

   public function isSms(): bool
   {
      return $this === self::SMS;
   }

   public function isEmail(): bool
   {
      return $this === self::EMAIL;
   }

   public function isWhatsapp(): bool
   {
      return $this === self::WHATSAPP;
   }
}
