<?php

namespace App\IdentityAndAccess\Presentation\Output;

use Symfony\Component\Serializer\Attribute\SerializedName;

final class SessionOutput
{
   public function __construct(
      public string $id,

      #[SerializedName('created_at')]
      public string $createdAt,

      #[SerializedName('ip_address')]
      public ?string $ipAddress = null,

      #[SerializedName('user_agent')]
      public ?string $userAgent = null,
   ) {}
}
