<?php

namespace App\IdentityAndAccess\Presentation\Input;

use App\IdentityAndAccess\Presentation\Contraints\PasswordConfirmation;
use App\IdentityAndAccess\Presentation\Contraints\PhoneOrEmail;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[PasswordConfirmation(['newPassword', 'passwordConfirmation'])]
final class ResetPasswordInput
{
   #[Assert\NotBlank()]
   #[PhoneOrEmail()]
   private ?string $identifier = null;

   #[SerializedName('otp_code')]
   #[Assert\NotBlank]
   #[Assert\Length(min: 6, max: 6)]
   private ?string $otpCode = null;

   #[SerializedName('new_password')]
   #[Assert\NotBlank]
   private ?string $newPassword = null;

   #[SerializedName('password_confirmation')]
   #[Assert\NotBlank]
   private ?string $passwordConfirmation = null;

   public function getOtpCode(): ?string
   {
      return $this->otpCode;
   }

   public function getNewPassword(): ?string
   {
      return $this->newPassword;
   }

   public function getPasswordConfirmation(): ?string
   {
      return $this->passwordConfirmation;
   }

   public function setOtpCode(?string $otpCode): static
   {
      $this->otpCode = $otpCode;
      return $this;
   }

   public function setNewPassword(?string $newPassword): static
   {
      $this->newPassword = $newPassword;

      return $this;
   }

   public function setPasswordConfirmation(?string $passwordConfirmation): static
   {
      $this->passwordConfirmation = $passwordConfirmation;
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
