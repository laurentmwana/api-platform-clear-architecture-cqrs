<?php

namespace App\IdentityAndAccess\Application\CommandHandler;

use App\IdentityAndAccess\Domain\Entity\User;
use App\IdentityAndAccess\Domain\Enums\OtpTypeEnum;
use App\IdentityAndAccess\Domain\Exception\AlreadyVerifiedException;
use App\IdentityAndAccess\Domain\Exception\OtpInvalidException;
use App\IdentityAndAccess\Domain\Repository\UserRepository;
use App\IdentityAndAccess\Domain\Service\OtpGenerator;
use App\IdentityAndAccess\Domain\ValueObject\OtpCode;
use App\IdentityAndAccess\Domain\ValueObject\OtpType;

abstract class AbstractOtpVerifier
{
   public function __construct(
      protected readonly UserRepository $user,
      protected readonly OtpGenerator $otp,
   ) {}

   protected function handle(
      User $user,
      OtpCode $code,
   ): OtpTypeEnum {
      if ($this->isAlreadyVerified($user)) {
         throw new AlreadyVerifiedException(
            $this->alreadyVerifiedMessage(),
         );
      }

      if (!$this->otp->consume(
         $user->getId(),
         $this->otpType(),
         $code,
      )) {
         throw new OtpInvalidException(
            $this->invalidOtpMessage(),
         );
      }

      $this->markAsVerified($user);

      $this->user->save($user);

      return $this->otpType()->toEnum();
   }

   abstract protected function otpType(): OtpType;

   abstract protected function isAlreadyVerified(User $user): bool;

   abstract protected function markAsVerified(User $user): void;

   abstract protected function alreadyVerifiedMessage(): string;

   abstract protected function invalidOtpMessage(): string;
}
