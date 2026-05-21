<?php

namespace App\IdentityAndAccess\Presentation\Input;

use App\IdentityAndAccess\Presentation\Contraints\PhoneOrEmail;
use Symfony\Component\Validator\Constraints as Assert;

final class VerifyOtpInput
{
   #[Assert\NotBlank()]
   #[Assert\Length(min: 6, max: 6)]
   private ?string $code = null;

   #[Assert\NotBlank()]
   #[PhoneOrEmail()]
   private ?string $identifier = null;

   public function getCode(): ?string
   {
      return $this->code;
   }

   public function setCode(?string $code): static
   {
      $this->code = $code;

      return $this;
   }

   public function getIdentifier(): ?string
   {
      return $this->identifier;
   }


   public function setIdentifier(?string $identifier): static
   {
      $this->identifier = $identifier;

      return $this;
   }
}
