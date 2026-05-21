<?php

namespace App\SharedContext\Application\Bus\Query;

interface QueryBus
{
   public function dispatch(object $object): mixed;
}
