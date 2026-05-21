<?php

namespace App\IdentityAndAccess\Domain\Service;

use App\IdentityAndAccess\Domain\Entity\OneTimePassword;

interface MagicLinkUrlGenerator
{
   public function generate(OneTimePassword $magicLink): string;
}
