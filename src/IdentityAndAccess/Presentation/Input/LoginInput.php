<?php

namespace App\IdentityAndAccess\Presentation\Input;

use App\IdentityAndAccess\Presentation\Contraints\PhoneOrEmail;
use Symfony\Component\Validator\Constraints as Assert;

final class LoginInput
{
   #[Assert\NotBlank()]
   #[PhoneOrEmail()]
   private ?string $identifier = null;

   #[Assert\NotBlank()]
   private ?string $password = null;

   public function getIdentifier(): ?string
   {
      return $this->identifier;
   }

   public function setIdentifier(?string $identifier): static
   {
      $this->identifier = $identifier;

      return $this;
   }

   public function getPassword(): ?string
   {
      return $this->password;
   }

   public function setPassword(?string $password): static
   {
      $this->password = $password;

      return $this;
   }
}
