<?php

namespace App\SharedContext\Infrastructure\Framework\Bus;

use App\SharedContext\Application\Bus\Message\MessageDispatcher;
use App\SharedContext\Domain\ValueObject\Message;
use App\SharedContext\Domain\Enums\DeliveryChannelEnum;
use Symfony\Component\Mailer\MailerInterface as SymfonyMailer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class SymfonyMessageDispatcher implements MessageDispatcher
{
   public function __construct(private SymfonyMailer $mailer) {}

   public function send(Message $message, DeliveryChannelEnum $channel): void
   {
      match ($channel) {
         DeliveryChannelEnum::EMAIL => $this->toEmail($message),
         DeliveryChannelEnum::SMS => $this->toSms($message),
         DeliveryChannelEnum::WHATSAPP => $this->toWhatsApp($message),
      };
   }

   private function toEmail(Message $message): void
   {
      $email = (new TemplatedEmail())
         ->from('group@locmobile.com')
         ->to($message->getRecipient()->value())
         ->subject($message->getSubject()->value)
         ->htmlTemplate($message->getTemplate()->value)
         ->context($message->getContext());

      $this->mailer->send($email);
   }

   private function toSms(Message $message): void {}

   private function toWhatsApp(Message $message): void {}
}
