<?php

namespace App\Bridges\Infrastructure\Persistence\Doctrine\Entity;

use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken as BaseRefreshToken;

class RefreshToken extends BaseRefreshToken
{
   public function __construct() {}
}
