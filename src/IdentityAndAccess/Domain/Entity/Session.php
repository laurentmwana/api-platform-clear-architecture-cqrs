<?php

namespace App\IdentityAndAccess\Domain\Entity;

use App\SharedContext\Domain\ValueObject\IpAddress;
use App\SharedContext\Domain\ValueObject\UserAgent;
use App\SharedContext\Domain\ValueObject\Uuid;
use DateTimeImmutable;

class Session
{
   private string $id;
   private string $userId;
   private ?string $userAgent = null;
   private ?string $ipAddress = null;
   private ?DateTimeImmutable $loggedOutAt = null;
   private DateTimeImmutable $createdAt;

   public function __construct(
      Uuid $id,
      Uuid $userId,
      ?UserAgent $userAgent = null,
      ?IpAddress $ipAddress = null,
      ?DateTimeImmutable $createdAt = null
   ) {
      $this->id = (string) $id;
      $this->userId = (string) $userId;
      $this->userAgent = $userAgent?->__toString();
      $this->ipAddress = $ipAddress?->__toString();
      $this->createdAt = $createdAt ?? new DateTimeImmutable();
   }

   public static function create(
      Uuid $id,
      Uuid $userId,
      ?UserAgent $userAgent = null,
      ?IpAddress $ipAddress = null,
   ): self {
      return new self($id, $userId, $userAgent, $ipAddress);
   }

   public function markForLogout(): void
   {
      $this->loggedOutAt = new DateTimeImmutable();
   }

   public function isloggedOut(): bool
   {
      return $this->loggedOutAt !== null;
   }

   public function getId(): Uuid
   {
      return new Uuid($this->id);
   }

   public function getUserId(): Uuid
   {
      return new Uuid($this->userId);
   }

   public function getUserAgent(): ?UserAgent
   {
      return $this->userAgent !== null
         ? new UserAgent($this->userAgent)
         : null;
   }

   public function getIpAddress(): ?IpAddress
   {
      return $this->ipAddress !== null
         ? new IpAddress($this->ipAddress)
         : null;
   }

   public function getCreatedAt(): DateTimeImmutable
   {
      return $this->createdAt;
   }

   public function getLoggedOutAt(): ?DateTimeImmutable
   {
      return $this->loggedOutAt;
   }
}
