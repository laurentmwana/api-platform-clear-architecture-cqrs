<?php

namespace App\IdentityAndAccess\Presentation\Output;

use App\IdentityAndAccess\Domain\Entity\User;
use DateTimeImmutable;
use Symfony\Component\Serializer\Attribute\SerializedName;

class UserMeOutput
{
   private string $id;
   private string $name;
   private string $email;
   private string $phone;

   /** @var array<int,string> $roles */
   private array $roles;

   #[SerializedName('email_verified_at')]
   private ?DateTimeImmutable $emailVerifiedAt = null;

   #[SerializedName('phone_verified_at')]
   private ?DateTimeImmutable $phoneVerifiedAt = null;

   #[SerializedName('email_verified')]
   private bool $isEmailVerified = false;

   #[SerializedName('phone_verified')]
   private bool $isPhoneVerified = false;

   #[SerializedName('created_at')]
   private DateTimeImmutable $createdAt;

   #[SerializedName('updated_at')]
   private DateTimeImmutable $updatedAt;

   /**
    * @param string $id
    * @param string $name
    * @param string $email
    * @param string $phone
    * @param array<int,string> $roles
    * @param DateTimeImmutable $createdAt
    * @param DateTimeImmutable $updatedAt
    * @param DateTimeImmutable|null $emailVerifiedAt
    * @param DateTimeImmutable|null $phoneVerifiedAt
    */
   public function __construct(
      string $id,
      string $name,
      string $email,
      string $phone,
      array $roles,
      DateTimeImmutable $createdAt,
      DateTimeImmutable $updatedAt,
      ?DateTimeImmutable $emailVerifiedAt = null,
      ?DateTimeImmutable $phoneVerifiedAt = null,
   ) {
      $this->id = $id;
      $this->name = $name;
      $this->email = $email;
      $this->phone = $phone;
      $this->roles = $roles;
      $this->emailVerifiedAt = $emailVerifiedAt;
      $this->phoneVerifiedAt = $phoneVerifiedAt;
      $this->createdAt = $createdAt;
      $this->updatedAt = $updatedAt;
      $this->isEmailVerified = $emailVerifiedAt !== null;
      $this->isPhoneVerified = $phoneVerifiedAt !== null;
   }

   public static function fromUser(User $user): self
   {
      return new self(
         id: $user->getId()->value(),
         name: $user->getName()->value(),
         email: $user->getEmail()->value(),
         phone: $user->getPhone()->value(),
         roles: $user->getRoles()->toArray(),
         emailVerifiedAt: $user->getEmailVerifiedAt(),
         phoneVerifiedAt: $user->getPhoneVerifiedAt(),
         createdAt: $user->getCreatedAt(),
         updatedAt: $user->getUpdatedAt(),
      );
   }

   public function getId(): string
   {
      return $this->id;
   }

   public function getName(): string
   {
      return $this->name;
   }

   public function getEmail(): string
   {
      return $this->email;
   }

   public function getPhone(): string
   {
      return $this->phone;
   }

   /**
    * @return array<int,string>
    */
   public function getRoles(): array
   {
      return $this->roles;
   }

   #[SerializedName('email_verified_at')]
   public function getEmailVerifiedAt(): ?DateTimeImmutable
   {
      return $this->emailVerifiedAt;
   }

   public function getPhoneVerifiedAt(): ?DateTimeImmutable
   {
      return $this->phoneVerifiedAt;
   }

   public function getIsEmailVerified(): bool
   {
      return $this->isEmailVerified;
   }

   public function getIsPhoneVerified(): bool
   {
      return $this->isPhoneVerified;
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
