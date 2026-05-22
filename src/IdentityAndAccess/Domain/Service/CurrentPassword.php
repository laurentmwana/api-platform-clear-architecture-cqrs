<?php

namespace App\IdentityAndAccess\Domain\Service;

use App\IdentityAndAccess\Domain\ValueObject\Password;

interface CurrentPassword
{
   public function confirm(Password $hashPassword, Password $plainPassword): void;
}
