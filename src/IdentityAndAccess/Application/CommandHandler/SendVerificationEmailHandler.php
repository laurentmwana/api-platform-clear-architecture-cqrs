<?php

namespace App\IdentityAndAccess\Application\CommandHandler;

use App\IdentityAndAccess\Application\Command\SendVerificationEmailCommand;
use App\IdentityAndAccess\Domain\Entity\User;
use App\IdentityAndAccess\Domain\Enums\OtpTypeEnum;
use App\IdentityAndAccess\Domain\ValueObject\DeliveryChannel;
use App\IdentityAndAccess\Domain\ValueObject\OtpType;
use App\SharedContext\Application\Bus\Command\CommandHandler;
use App\SharedContext\Domain\Enums\MessageSubjectEnum;
use App\SharedContext\Domain\Enums\MessageTemplateEnum;

final class SendVerificationEmailHandler extends AbstractSendOtpSender implements CommandHandler
{
   public function __invoke(SendVerificationEmailCommand $command): OtpTypeEnum
   {
      $user = $command->getUser();

      return $this->handle($user, $user->getEmail());
   }

   protected function isAlreadyVerified(User $user): bool
   {
      return $user->isEmailVerified();
   }

   protected function alreadyVerifiedMessage(): string
   {
      return "Email Already verified";
   }

   protected function otpType(): OtpType
   {
      return OtpType::verifyEmail();
   }

   protected function channel(): DeliveryChannel
   {
      return DeliveryChannel::email();
   }

   protected function template(): MessageTemplateEnum
   {
      return MessageTemplateEnum::VERIFY_EMAIL;
   }

   protected function subject(): MessageSubjectEnum
   {
      return MessageSubjectEnum::VERIFY_EMAIL;
   }
}
