<?php

namespace App\SharedContext\Application\Bus\Event;

interface EventBus
{
   public function dispatch(object $event): void;
}
