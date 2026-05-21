<?php

namespace App\IdentityAndAccess\Infrastructure\Persistence\Fixtures;

use App\IdentityAndAccess\Domain\Entity\OneTimePassword;
use App\IdentityAndAccess\Domain\Entity\User;
use App\IdentityAndAccess\Domain\ValueObject\DeliveryChannel;
use App\IdentityAndAccess\Domain\ValueObject\OtpCode;
use App\IdentityAndAccess\Domain\ValueObject\OtpType;
use App\SharedContext\Domain\ValueObject\Uuid as ValueObjectUuid;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

final class OtpFixtures extends Fixture
{
   public static function createOne(
      User $user,
      OtpType $type,
      DeliveryChannel $deliveryChannel,
      ?string $code = null,
   ): OneTimePassword {
      $faker = Factory::create();

      return OneTimePassword::create(
         id: new ValueObjectUuid($faker->uuid()),
         userId: $user->getId(),
         code: new OtpCode($code ?? $faker->numerify('######')),
         type: $type,
         deliveryMethod: $deliveryChannel,
      );
   }

   public static function createAndPersist(
      ObjectManager $manager,
      User $user,
      OtpType $type,
      DeliveryChannel $deliveryChannel,
      ?string $code = null,
   ): OneTimePassword {
      $otp = self::createOne(
         $user,
         $type,
         $deliveryChannel,
         $code
      );

      $manager->persist($otp);

      return $otp;
   }

   public function load(ObjectManager $manager): void {}
}
