<?php

namespace App\IdentityAndAccess\Presentation\Input;

use App\IdentityAndAccess\Presentation\Contraints\PhoneOrEmail;
use Symfony\Component\Validator\Constraints as Assert;

final class IdentifierInput
{
   #[Assert\NotBlank()]
   #[PhoneOrEmail()]
   private ?string $identifier = null;

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
