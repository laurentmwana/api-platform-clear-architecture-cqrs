<?php

namespace App\IdentityAndAccess\Application\CommandHandler;

use App\IdentityAndAccess\Application\Command\SendVerificationPhoneCommand;
use App\IdentityAndAccess\Domain\Entity\User;
use App\IdentityAndAccess\Domain\Enums\OtpTypeEnum;
use App\IdentityAndAccess\Domain\ValueObject\DeliveryChannel;
use App\IdentityAndAccess\Domain\ValueObject\OtpType;
use App\SharedContext\Application\Bus\Command\CommandHandler;
use App\SharedContext\Domain\Enums\MessageSubjectEnum;
use App\SharedContext\Domain\Enums\MessageTemplateEnum;

final class SendVerificationPhoneHandler extends AbstractSendOtpSender implements CommandHandler
{
   public function __invoke(SendVerificationPhoneCommand $command): OtpTypeEnum
   {
      $user = $command->getUser();

      return $this->handle($user, $user->getPhone());
   }

   protected function isAlreadyVerified(User $user): bool
   {
      return $user->isPhoneVerified();
   }

   protected function alreadyVerifiedMessage(): string
   {
      return "Phone Already verified";
   }

   protected function otpType(): OtpType
   {
      return OtpType::verifyPhone();
   }

   protected function channel(): DeliveryChannel
   {
      return DeliveryChannel::sms();
   }

   protected function template(): MessageTemplateEnum
   {
      return MessageTemplateEnum::VERIFY_PHONE_EMAIL;
   }

   protected function subject(): MessageSubjectEnum
   {
      return MessageSubjectEnum::VERIFY_PHONE;
   }
}
