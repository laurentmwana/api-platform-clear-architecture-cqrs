<?php

namespace App\SharedContext\Domain\ValueObject;

use App\IdentityAndAccess\Domain\ValueObject\EmailOrPhone;
use App\SharedContext\Domain\Enums\MessageTemplateEnum;
use App\SharedContext\Domain\Enums\MessageSubjectEnum;

final class Message
{
   /**
    * @param EmailOrPhone $recipient
    * @param MessageTemplateEnum $template
    * @param MessageSubjectEnum|null $subject
    * @param array<string,mixed> $context
    */
   public function __construct(
      private EmailOrPhone $recipient,
      private MessageTemplateEnum $template,
      private ?MessageSubjectEnum $subject = null,
      private array $context = [],
   ) {}

   public function getRecipient(): EmailOrPhone
   {
      return $this->recipient;
   }

   public function getTemplate(): MessageTemplateEnum
   {
      return $this->template;
   }

   public function getSubject(): ?MessageSubjectEnum
   {
      return $this->subject;
   }

   /**
    *
    * @return array<string,mixed>
    */
   public function getContext(): array
   {
      return $this->context;
   }

   /**
    * @param array<string,mixed> $context
    * @return self
    */
   public function withContext(array $context): self
   {
      $new = clone $this;
      $new->context = array_merge($this->context, $context);
      return $new;
   }
}
