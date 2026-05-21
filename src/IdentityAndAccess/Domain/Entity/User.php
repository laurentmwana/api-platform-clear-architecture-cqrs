<?php

namespace App\IdentityAndAccess\Domain\Entity;

use App\SharedContext\Domain\ValueObject\Email;
use App\SharedContext\Domain\ValueObject\Name;
use App\SharedContext\Domain\ValueObject\Uuid;
use App\IdentityAndAccess\Domain\ValueObject\Password;
use App\IdentityAndAccess\Domain\ValueObject\Roles;
use App\SharedContext\Domain\ValueObject\Phone;
use DateTimeImmutable;

final class User
{
   private string $id;
   private string $name;
   private string $email;
   private string $phone;
   private string $password;
   private string $roles;
   private DateTimeImmutable $createdAt;
   private ?DateTimeImmutable $updatedAt = null;
   private ?DateTimeImmutable $phoneVerifiedAt = null;
   private ?DateTimeImmutable $emailVerifiedAt = null;

   public function __construct(
      Uuid $id,
      Name $name,
      Email $email,
      Phone $phone,
      Password $password,
      ?Roles $roles = null,
      ?DateTimeImmutable $createdAt = null,
      ?DateTimeImmutable $updatedAt = null
   ) {
      $this->id = $id->value();
      $this->name = $name->value();
      $this->email = $email->value();
      $this->phone = $phone->value();
      $this->password = $password->value();

      $roles = $roles ?? Roles::default();
      $this->roles = $roles->toJson();

      $this->createdAt = $createdAt ?? new DateTimeImmutable();
      $this->updatedAt = $updatedAt ?? new DateTimeImmutable();
   }

   public static function create(
      Uuid $id,
      Name $name,
      Email $email,
      Phone $phone,
      Password $password,
      ?Roles $roles = null
   ): self {
      return new self(
         $id,
         $name,
         $email,
         $phone,
         $password,
         $roles ?? Roles::default()
      );
   }

   public function changePassword(Password $newPassword): void
   {
      $this->password = $newPassword->value();
      $this->updatedAt = new DateTimeImmutable();
   }

   public function isEmailVerified(): bool
   {
      return $this->emailVerifiedAt !== null;
   }

   public function isPhoneVerified(): bool
   {
      return $this->phoneVerifiedAt !== null;
   }

   public function markEmailAsVerified(): void
   {
      $this->emailVerifiedAt = new DateTimeImmutable();
      $this->updatedAt = new DateTimeImmutable();
   }

   public function markPhoneAsVerified(): void
   {
      $this->phoneVerifiedAt = new DateTimeImmutable();
      $this->updatedAt = new DateTimeImmutable();
   }

   public function markEmailAsUnVerified(): void
   {
      $this->emailVerifiedAt = null;
      $this->updatedAt = new DateTimeImmutable();
   }

   public function markPhoneAsUnVerified(): void
   {
      $this->phoneVerifiedAt = null;
      $this->updatedAt = new DateTimeImmutable();
   }

   public function getId(): Uuid
   {
      return new Uuid($this->id);
   }

   public function getName(): Name
   {
      return new Name($this->name);
   }

   public function getEmail(): Email
   {
      return new Email($this->email);
   }

   public function getPhone(): Phone
   {
      return new Phone($this->phone);
   }

   public function getPassword(): Password
   {
      return Password::fromHash($this->password);
   }

   public function getRoles(): Roles
   {
      return Roles::fromJson($this->roles);
   }

   public function getCreatedAt(): DateTimeImmutable
   {
      return $this->createdAt;
   }

   public function getUpdatedAt(): ?DateTimeImmutable
   {
      return $this->updatedAt;
   }
}
