<?php

namespace App\IdentityAndAccess\Application\CommandHandler;

use App\IdentityAndAccess\Domain\Enums\OtpTypeEnum;
use App\IdentityAndAccess\Domain\Repository\UserRepository;
use App\IdentityAndAccess\Domain\Service\OtpGenerator;
use App\IdentityAndAccess\Domain\ValueObject\DeliveryChannel;
use App\IdentityAndAccess\Domain\ValueObject\EmailOrPhone;
use App\IdentityAndAccess\Domain\ValueObject\OtpType;
use App\SharedContext\Application\Bus\Message\MessageBus;
use App\SharedContext\Application\Command\SendMessageCommand;
use App\SharedContext\Domain\Enums\DeliveryChannelEnum;
use App\SharedContext\Domain\Enums\MessageSubjectEnum;
use App\SharedContext\Domain\Enums\MessageTemplateEnum;
use App\SharedContext\Domain\ValueObject\Message;

abstract class AbstractRecoverySender
{
   public function __construct(
      protected OtpGenerator $otpGenerator,
      protected MessageBus $messageBus,
      private UserRepository $user,
   ) {}

   public function handle(EmailOrPhone $identifier, DeliveryChannelEnum $via): OtpTypeEnum
   {
      $user = $this->user->findByIdentifier($identifier);
      $type = $this->otpType();

      if (null === $user) {
         return $type->toEnum();
      }

      $channel = DeliveryChannel::fromEnum($via);

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
               recipient: $identifier,
               template: $this->template(),
               subject: $this->subject(),
               context: $context,
            ),
            $channel->toEnum(),
         )
      );

      return $type->toEnum();
   }

   abstract protected function otpType(): OtpType;

   abstract protected function template(): MessageTemplateEnum;

   abstract protected function subject(): MessageSubjectEnum;
}
