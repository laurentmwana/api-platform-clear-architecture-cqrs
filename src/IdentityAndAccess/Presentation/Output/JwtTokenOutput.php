<?php

namespace App\IdentityAndAccess\Presentation\Output;

use Symfony\Component\Serializer\Attribute\SerializedName;


final readonly class JwtTokenOutput
{
   #[SerializedName('access_token')]
   private string $accessToken;

   #[SerializedName('refresh_token')]
   private string $refreshToken;

   /**
    * @param array{access_token:string,refresh_token:string} $tokens
    */
   public function __construct(array $tokens)
   {
      $this->accessToken = $tokens['access_token'];
      $this->refreshToken = $tokens['refresh_token'];
   }

   public function getAccessToken(): string
   {
      return $this->accessToken;
   }

   public function getRefreshToken(): string
   {
      return $this->refreshToken;
   }
}
