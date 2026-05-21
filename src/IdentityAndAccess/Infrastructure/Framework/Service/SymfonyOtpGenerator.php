<?php

namespace App\IdentityAndAccess\Infrastructure\Framework\Service;

use App\IdentityAndAccess\Domain\Entity\OneTimePassword;
use App\IdentityAndAccess\Domain\Entity\User;
use App\IdentityAndAccess\Domain\Repository\OneTimePasswordRepository;
use App\IdentityAndAccess\Domain\Service\OtpGenerator;
use App\IdentityAndAccess\Domain\ValueObject\DeliveryChannel;
use App\IdentityAndAccess\Domain\ValueObject\OtpCode;
use App\IdentityAndAccess\Domain\ValueObject\OtpType;
use App\SharedContext\Domain\Service\UuidGenerator;
use App\SharedContext\Domain\ValueObject\Uuid;
use Symfony\Component\String\ByteString;

class SymfonyOtpGenerator implements OtpGenerator
{
   public function __construct(
      private OneTimePasswordRepository $otpRepository,
      private UuidGenerator $uuidGenerator,
   ) {}

   public function generate(User $user, OtpType $type, DeliveryChannel $method): OneTimePassword
   {
      $userId = $user->getId();

      $existingOtp = $this->otpRepository->findValidByUserId($userId, $type);

      if ($existingOtp) {
         $this->otpRepository->remove($existingOtp);
      }

      $newOtp = OneTimePassword::create(
         $this->uuidGenerator->generate(),
         $user->getId(),
         $this->getOtpCode(),
         $type,
         $method,
      );

      $this->otpRepository->save($newOtp);

      return $newOtp;
   }

   public function consume(Uuid $userId, OtpType $type, OtpCode $code): bool
   {
      $oneTimePassword = $this->otpRepository->findValidByUserId($userId, $type);

      if (!$oneTimePassword) {
         return false;
      }

      if ($oneTimePassword->isExpired()) {
         $this->otpRepository->remove($oneTimePassword);
         return false;
      }

      if (!$oneTimePassword->getCode()->equals($code)) {
         $oneTimePassword->markAsFailed();

         if ($oneTimePassword->isExpired()) {
            $this->otpRepository->remove($oneTimePassword);
         } else {
            $this->otpRepository->save($oneTimePassword);
         }

         return false;
      }

      $oneTimePassword->markAsUsed();
      $this->otpRepository->save($oneTimePassword);

      return true;
   }

   private function getOtpCode(): OtpCode
   {
      $code = ByteString::fromRandom(6, '0123456789')->toString();
      return new OtpCode($code);
   }
}
