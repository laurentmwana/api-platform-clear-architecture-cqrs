<?php

namespace App\Tests\ApiTestCase;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\IdentityAndAccess\Domain\Entity\OneTimePassword;
use App\IdentityAndAccess\Domain\Entity\User;
use App\IdentityAndAccess\Domain\ValueObject\DeliveryChannel;
use App\IdentityAndAccess\Domain\ValueObject\OtpType;
use App\IdentityAndAccess\Infrastructure\Persistence\Fixtures\OtpFixtures;
use App\IdentityAndAccess\Infrastructure\Persistence\Fixtures\UserFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemPoolInterface;

abstract class AbstractApiTestCase extends ApiTestCase
{
   private ?string $token = null;

   protected static ?bool $alwaysBootKernel = true;

   protected EntityManagerInterface $entityManager;

   protected function setUp(): void
   {
      parent::setUp();
      self::bootKernel();

      /** @var EntityManagerInterface $em */
      $em = static::getContainer()->get(EntityManagerInterface::class);

      $this->entityManager = $em;
   }

   protected function getUserByEmail(string $email): ?User
   {
      return $this->entityManager
         ->getRepository(User::class)
         ->findOneBy(['email' => $email]);
   }

   protected function getOtpByUserId(string $userId): ?OneTimePassword
   {
      return $this->entityManager
         ->getRepository(OneTimePassword::class)
         ->findOneBy(['userId' => $userId]);
   }

   protected function getOtpCodeForUser(string $email): ?string
   {
      $user = $this->getUserByEmail($email);

      if (!$user) {
         return null;
      }

      $otp = $this->getOtpByUserId($user->getId());

      return $otp ? $otp->getCode()->value() : null;
   }

   protected function createOtp(
      User $user,
      OtpType $type,
      DeliveryChannel $deliveryChannel,
      ?string $code = null,
   ): OneTimePassword {
      $otp = OtpFixtures::createAndPersist(
         $this->getManager(),
         $user,
         $type,
         $deliveryChannel,
         $code,
      );

      $this->getManager()->flush();

      return $otp;
   }

   protected function expireOtp(string $otpId): void
   {
      $otp = $this->entityManager->find(OneTimePassword::class, $otpId);

      if ($otp) {
         $otp->markAsUsed();

         $this->entityManager->flush();
      }
   }

   public function getManager(): EntityManagerInterface
   {
      return $this->entityManager;
   }

   protected function createClientWithCredentials(?string $token = null): Client
   {
      $token = $token ?: $this->getToken();

      return static::createClient([], [
         'headers' => [
            'authorization' => 'Bearer ' . $token,
         ],
      ]);
   }

   /**
    * @return string[]
    */
   public function getHeadersContentJson(): array
   {
      return [
         'accept' => 'application/json',
         'Content-Type' => 'application/json',
      ];
   }

   /**
    * @return string[]
    */
   public function getHeadersAccept(): array
   {
      return [
         'accept' => 'application/json',
      ];
   }

   /**
    * @param array<string, mixed> $body
    * @return string
    */
   protected function getToken(array $body = []): string
   {
      if (null !== $this->token) {
         return $this->token;
      }

      $response = static::createClient()
         ->request('POST', '/api/auth/login', [
            'json' => $body ?: [
               'identifier' => 'admin@example.com',
               'password' => 'password',
            ],
            'headers' => $this->getHeadersContentJson()
         ]);

      $this->assertResponseIsSuccessful();

      /** @var array{access_token: string, refresh_token: string} $data */
      $data = $response->toArray();

      $this->token = $data['access_token'];

      return $this->token;
   }

   protected function clearRateLimitCache(): void
   {
      /** @var CacheItemPoolInterface $cachePool */
      $cachePool = static::getContainer()->get('cache.rate_limiter');

      $cachePool->clear();
   }

   public function createUser(
      ?string $email = null,
      ?string $phone = null,
      bool $emailUnverified = true,
      bool $phoneUnverified = true
   ): User {
      $user = UserFixtures::createOne(
         $email,
         $phone,
         $emailUnverified,
         $phoneUnverified
      );

      $this->save($user);

      return $user;
   }

   public function save(object $object): object
   {
      $this->getManager()->persist($object);
      $this->getManager()->flush();

      return $object;
   }

   public function remove(object $object): object
   {
      $this->getManager()->remove($object);
      $this->getManager()->flush();

      return $object;
   }
}
