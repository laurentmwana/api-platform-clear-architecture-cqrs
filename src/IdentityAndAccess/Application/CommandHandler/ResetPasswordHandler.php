<?php

namespace App\IdentityAndAccess\Application\CommandHandler;

use App\IdentityAndAccess\Application\Command\ResetPasswordCommand;
use App\IdentityAndAccess\Domain\Exception\OtpInvalidException;
use App\IdentityAndAccess\Domain\Repository\UserRepository;
use App\IdentityAndAccess\Domain\Service\OtpGenerator;
use App\IdentityAndAccess\Domain\Service\PasswordHasher;
use App\IdentityAndAccess\Domain\ValueObject\OtpType;
use App\SharedContext\Application\Bus\Command\CommandHandler;

final readonly class ResetPasswordHandler implements CommandHandler
{
   public function __construct(
      private UserRepository $userRepository,
      private OtpGenerator $otpGenerator,
      private PasswordHasher $passwordHasher,
   ) {}

   public function __invoke(ResetPasswordCommand $command): void
   {
      $identifier = $command->getIdentifier();

      $user = $this->userRepository->findByIdentifier($identifier);

      if ($user === null) {
         throw new OtpInvalidException(
            'Invalid OTP code.',
         );
      }

      $type = OtpType::passwordReset();

      $isConsumed = $this->otpGenerator->consume(
         $user->getId(),
         $type,
         $command->getOtpCode(),
      );

      if (!$isConsumed) {
         throw new OtpInvalidException(
            'Invalid OTP code.',
         );
      }

      $hashedPassword = $this->passwordHasher->hash(
         $command->getNewPassword(),
      );

      $user->changePassword($hashedPassword);

      $this->userRepository->save($user);
   }
}
