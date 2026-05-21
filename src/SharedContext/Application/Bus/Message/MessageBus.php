<?php

namespace App\SharedContext\Application\Bus\Message;

interface MessageBus
{
   public function dispatch(object $object): void;
}
