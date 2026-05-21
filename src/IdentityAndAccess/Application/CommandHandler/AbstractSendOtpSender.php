<?php

namespace App\IdentityAndAccess\Application\CommandHandler;

use App\IdentityAndAccess\Domain\Entity\User;
use App\IdentityAndAccess\Domain\Enums\OtpTypeEnum;
use App\IdentityAndAccess\Domain\Exception\AlreadyVerifiedException;
use App\IdentityAndAccess\Domain\Service\OtpGenerator;
use App\IdentityAndAccess\Domain\ValueObject\DeliveryChannel;
use App\IdentityAndAccess\Domain\ValueObject\EmailOrPhone;
use App\IdentityAndAccess\Domain\ValueObject\OtpType;
use App\SharedContext\Application\Bus\Message\MessageBus;
use App\SharedContext\Application\Command\SendMessageCommand;
use App\SharedContext\Domain\Enums\MessageSubjectEnum;
use App\SharedContext\Domain\Enums\MessageTemplateEnum;
use App\SharedContext\Domain\ValueObject\Email;
use App\SharedContext\Domain\ValueObject\Message;
use App\SharedContext\Domain\ValueObject\Phone;

abstract class AbstractSendOtpSender
{
   public function __construct(
      protected OtpGenerator $otpGenerator,
      protected MessageBus $messageBus,
   ) {}

   public function handle(User $user, Email|Phone $identifier): OtpTypeEnum
   {
      if ($this->isAlreadyVerified($user)) {
         throw new AlreadyVerifiedException(
            $this->alreadyVerifiedMessage()
         );
      }

      $type = $this->otpType();
      $channel = $this->channel();

      $otp = $this->otpGenerator->generate($user, $type, $channel);

      $context = [
         'username' => $user->getName(),
         'code' => $otp->getCode()->value(),
         'attempts' => $otp->getAttempts()->value(),
         'expiresAt' => $otp->getExpiresAt(),
      ];

      $this->messageBus->dispatch(
         new SendMessageCommand(
            new Message(
               recipient: new EmailOrPhone($identifier),
               template: $this->template(),
               subject: $this->subject(),
               context: $context,
            ),
            $channel->toEnum(),
         )
      );

      return $type->toEnum();
   }

   abstract protected function isAlreadyVerified(User $user): bool;

   abstract protected function alreadyVerifiedMessage(): string;

   abstract protected function otpType(): OtpType;

   abstract protected function channel(): DeliveryChannel;

   abstract protected function template(): MessageTemplateEnum;

   abstract protected function subject(): MessageSubjectEnum;
}
