<?php

namespace App\SharedContext\Presentation\Output;

final class MessageOutput
{
   public function __construct(private string $message) {}

   public function getMessage(): string
   {
      return $this->message;
   }
}
