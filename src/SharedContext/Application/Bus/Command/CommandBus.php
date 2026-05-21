<?php

namespace App\SharedContext\Application\Bus\Command;

interface CommandBus
{
   public function dispatch(object $object): mixed;
}
