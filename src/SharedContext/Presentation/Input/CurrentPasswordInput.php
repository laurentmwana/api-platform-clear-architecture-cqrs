<?php

namespace App\SharedContext\Presentation\Input;

use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class CurrentPasswordInput
{
   #[SerializedName('current_password')]
   #[Assert\NotBlank()]
   private ?string $currentPassword = null;

   public function getCurrentPassword(): ?string
   {
      return $this->currentPassword;
   }

   public function setCurrentPassword(?string $currentPassword): static
   {
      $this->currentPassword = $currentPassword;

      return $this;
   }
}
