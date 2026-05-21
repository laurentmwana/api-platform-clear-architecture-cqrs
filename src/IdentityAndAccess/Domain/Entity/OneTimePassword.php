<?php

namespace App\IdentityAndAccess\Domain\Entity;

use App\IdentityAndAccess\Domain\ValueObject\DeliveryChannel;
use App\IdentityAndAccess\Domain\ValueObject\OtpCode;
use App\IdentityAndAccess\Domain\ValueObject\OtpType;
use App\SharedContext\Domain\ValueObject\Attempts;
use App\SharedContext\Domain\ValueObject\Uuid;
use DateTimeImmutable;

class OneTimePassword
{
   private string $id;
   private string $userId;
   private string $code;
   private string $type;
   private string $deliveryMethod;

   private DateTimeImmutable $expiresAt;
   private DateTimeImmutable $createdAt;
   private DateTimeImmutable $updatedAt;

   private int $attempts;

   private function __construct(
      Uuid $id,
      Uuid $userId,
      OtpCode $code,
      OtpType $type,
      DeliveryChannel $deliveryMethod,
      ?DateTimeImmutable $createdAt = null,
      ?DateTimeImmutable $updatedAt = null,
   ) {
      $this->id = (string) $id;
      $this->userId = (string) $userId;
      $this->code = (string) $code;
      $this->type = (string) $type;
      $this->deliveryMethod = $deliveryMethod->value();

      $this->expiresAt = new DateTimeImmutable(sprintf("+%d minutes", $type->getExpirationMinutes()));
      $this->attempts = $type->getMaxAttempts();

      $this->createdAt = $createdAt ?? new DateTimeImmutable();
      $this->updatedAt = $updatedAt ?? new DateTimeImmutable();
   }

   public static function create(
      Uuid $id,
      Uuid $userId,
      OtpCode $code,
      OtpType $type,
      DeliveryChannel $deliveryMethod,
   ): self {
      return new self(
         $id,
         $userId,
         $code,
         $type,
         $deliveryMethod,
      );
   }

   public function markAsUsed(): void
   {
      $this->updatedAt = new DateTimeImmutable();
      $this->expiresAt = new DateTimeImmutable();
   }

   public function markAsFailed(): void
   {
      $this->attempts--;
      $this->updatedAt = new DateTimeImmutable();
   }

   public function isExpired(): bool
   {
      return $this->expiresAt < new DateTimeImmutable() || $this->attempts <= 0;
   }

   public function isPending(): bool
   {
      return !$this->isExpired();
   }

   public function hasRemainingAttempts(): bool
   {
      return $this->attempts > 0;
   }

   public function getId(): Uuid
   {
      return new Uuid($this->id);
   }

   public function getUserId(): Uuid
   {
      return new Uuid($this->userId);
   }

   public function getCode(): OtpCode
   {
      return new OtpCode($this->code);
   }

   public function getType(): OtpType
   {
      return new OtpType($this->type);
   }

   public function getDeliveryMethod(): DeliveryChannel
   {
      return DeliveryChannel::fromString($this->deliveryMethod);
   }

   public function getAttempts(): Attempts
   {
      return new Attempts($this->attempts);
   }

   public function getRemainingAttempts(): int
   {
      return $this->attempts;
   }

   public function getExpiresAt(): DateTimeImmutable
   {
      return $this->expiresAt;
   }

   public function getCreatedAt(): DateTimeImmutable
   {
      return $this->createdAt;
   }

   public function getUpdatedAt(): DateTimeImmutable
   {
      return $this->updatedAt;
   }
}
