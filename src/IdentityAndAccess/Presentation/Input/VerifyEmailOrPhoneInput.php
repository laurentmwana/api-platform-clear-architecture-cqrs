<?php

namespace App\IdentityAndAccess\Presentation\Input;

use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

final class VerifyEmailOrPhoneInput
{
   #[SerializedName('otp_code')]
   #[Assert\NotBlank()]
   #[Assert\Length(min: 6, max: 6)]
   private ?string $otpCode = null;

   public function getOtpCode(): ?string
   {
      return $this->otpCode;
   }

   public function setOtpCode(?string $otpCode): static
   {
      $this->otpCode = $otpCode;

      return $this;
   }
}
