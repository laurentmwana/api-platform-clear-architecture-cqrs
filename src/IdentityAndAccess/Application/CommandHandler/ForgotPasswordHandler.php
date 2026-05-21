<?php

declare(strict_types=1);

namespace App\IdentityAndAccess\Application\CommandHandler;

use App\IdentityAndAccess\Application\Command\ForgotPasswordCommand;
use App\IdentityAndAccess\Domain\Enums\OtpTypeEnum;
use App\IdentityAndAccess\Domain\ValueObject\OtpType;
use App\SharedContext\Application\Bus\Command\CommandHandler;
use App\SharedContext\Domain\Enums\MessageSubjectEnum;
use App\SharedContext\Domain\Enums\MessageTemplateEnum;

final class ForgotPasswordHandler extends AbstractRecoverySender implements CommandHandler
{
   public function __invoke(ForgotPasswordCommand $command): OtpTypeEnum
   {
      $identifier = $command->getIdentifier();
      $via = $identifier->getDeliveryMethod();

      return $this->handle($identifier, $via);
   }

   protected function otpType(): OtpType
   {
      return OtpType::passwordReset();
   }

   protected function template(): MessageTemplateEnum
   {
      return MessageTemplateEnum::PASSWORD_RESET_EMAIL;
   }

   protected function subject(): MessageSubjectEnum
   {
      return MessageSubjectEnum::PASSWORD_RESET;
   }
}
