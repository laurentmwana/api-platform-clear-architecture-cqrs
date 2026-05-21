<?php

namespace App\Tests\ApiTestCase\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor;

use App\Tests\ApiTestCase\AbstractApiTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class MagicLoginProcessorTest extends AbstractApiTestCase
{
   use ResetDatabase, Factories;

   protected function setUp(): void
   {
      parent::setUp();
   }

   public function testLoginSuccessWithEmail(): void
   {
      $email = 'test@example.com';

      $this->createUser($email, '+243820110123');

      $response = static::createClient()->request('POST', '/api/auth/magic-login', [
         'json' => [
            'identifier' => $email,
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseIsSuccessful();
      $this->assertResponseStatusCodeSame(200);

      $data = $response->toArray();

      $this->assertArrayHasKey('message', $data);
      $this->assertArrayHasKey('expires_minutes', $data);
      $this->assertArrayHasKey('attempts', $data);
      $this->assertNotEmpty($data['message']);
      $this->assertNotEmpty($data['expires_minutes']);
      $this->assertNotEmpty($data['attempts']);
   }

   public function testLoginSuccessWithPhone(): void
   {
      $phone = '+243820110123';

      $this->createUser('test@example.com', $phone);

      $response = static::createClient()->request('POST', '/api/auth/magic-login', [
         'json' => [
            'identifier' => $phone,
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseIsSuccessful();
      $this->assertResponseStatusCodeSame(200);

      $data = $response->toArray();

      $this->assertArrayHasKey('message', $data);
      $this->assertArrayHasKey('expires_minutes', $data);
      $this->assertArrayHasKey('attempts', $data);
      $this->assertNotEmpty($data['message']);
      $this->assertNotEmpty($data['expires_minutes']);
      $this->assertNotEmpty($data['attempts']);
   }

   public function testLoginWithPhoneWithoutPlusShouldWork(): void
   {
      $phone = '+243820110123';
      $phoneWithoutPlus = '243820110123';

      $this->createUser('test@example.com', $phone);

      $response = static::createClient()->request('POST', '/api/auth/magic-login', [
         'json' => ['identifier' => $phoneWithoutPlus],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseIsSuccessful();
      $this->assertResponseStatusCodeSame(200);

      $data = $response->toArray();

      $this->assertArrayHasKey('message', $data);
      $this->assertArrayHasKey('expires_minutes', $data);
      $this->assertArrayHasKey('attempts', $data);
      $this->assertNotEmpty($data['message']);
      $this->assertNotEmpty($data['expires_minutes']);
      $this->assertNotEmpty($data['attempts']);
   }

   public function testLoginWithPhoneLocalFormatShouldFail(): void
   {
      $phone = '+243820110123';

      $this->createUser('test@example.com', $phone);

      static::createClient()->request('POST', '/api/auth/magic-login', [
         'json' => [
            'identifier' => '08270110123',
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(422);
   }

   public function testLoginFailureEmptyIdentifier(): void
   {
      static::createClient()->request('POST', '/api/auth/magic-login', [
         'json' => [
            'identifier' => "",
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(422);
   }

   public function testLoginFailureWithNonExistentPhone(): void
   {
      $this->createUser('test3@example.com', '+243800110125');

      static::createClient()->request('POST', '/api/auth/magic-login', [
         'json' => [
            'identifier' => '+243800110120',
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(200);
   }

   public function testLoginFailureUserNotFoundByEmail(): void
   {
      static::createClient()->request('POST', '/api/auth/magic-login', [
         'json' => ['identifier' => 'nonexistent@example.com'],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(200);
   }

   public function testLoginFailureUserNotFoundByPhone(): void
   {
      static::createClient()->request('POST', '/api/auth/magic-login', [
         'json' => [
            'identifier' => '+243977878852',
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(200);
   }


   public function testVerifySuccessWithValidCode(): void
   {
      $email = 'test@example.com';
      $this->createUser($email, '+243820110123');

      $response = static::createClient()->request('POST', '/api/auth/magic-login', [
         'json' => ['identifier' => $email],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseIsSuccessful();

      $user = $this->getUserByEmail($email);
      $otp = $this->getOtpByUserId($user->getId());

      $response = static::createClient()->request('POST', '/api/auth/magic-login/verify', [
         'json' => [
            'code' => $otp->getCode()->value(),
            'identifier' => $email,
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseIsSuccessful();
      $this->assertResponseStatusCodeSame(200);

      $data = $response->toArray();
      $this->assertArrayHasKey('access_token', $data);
      $this->assertNotEmpty($data['access_token']);
   }

   public function testVerifyFailsWithInvalidCode(): void
   {
      $email = 'test@example.com';
      $this->createUser($email, '+243820110123');

      static::createClient()->request('POST', '/api/auth/magic-login', [
         'json' => ['identifier' => $email],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $response = static::createClient()->request('POST', '/api/auth/magic-login/verify', [
         'json' => [
            'code' => '000000',
            'identifier' => $email,
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(401);
   }

   public function testVerifyFailsWithExpiredCode(): void
   {
      $email = 'test@example.com';
      $this->createUser($email, '+243820110123');

      static::createClient()->request('POST', '/api/auth/magic-login', [
         'json' => ['identifier' => $email],
         'headers' => $this->getHeadersContentJson(),
      ]);

      // Simuler l'expiration du code
      $user = $this->getUserByEmail($email);
      $otp = $this->getOtpByUserId($user->getId());
      $this->expireOtp($otp->getId());

      $response = static::createClient()->request('POST', '/api/auth/magic-login/verify', [
         'json' => [
            'code' => $otp->getCode()->value(),
            'identifier' => $email,
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(401);
   }

   public function testVerifyFailsWithMaxAttemptsExceeded(): void
   {
      $email = 'test@example.com';
      $this->createUser($email, '+243820110123');

      static::createClient()->request('POST', '/api/auth/magic-login', [
         'json' => ['identifier' => $email],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $maxAttempts = 3;

      for ($i = 0; $i < $maxAttempts; $i++) {
         static::createClient()->request('POST', '/api/auth/magic-login/verify', [
            'json' => [
               'code' => '000000',
               'identifier' => $email,
            ],
            'headers' => $this->getHeadersContentJson(),
         ]);
      }

      $response = static::createClient()->request('POST', '/api/auth/magic-login/verify', [
         'json' => [
            'code' => '000000',
            'identifier' => $email,
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(401);
   }

   public function testVerifyFailsWithEmptyCode(): void
   {
      $email = 'test@example.com';
      $this->createUser($email, '+243820110123');

      static::createClient()->request('POST', '/api/auth/magic-login/verify', [
         'json' => [
            'code' => '',
            'identifier' => $email,
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(422);
   }

   public function testVerifyFailsWithUserNotFound(): void
   {
      static::createClient()->request('POST', '/api/auth/magic-login/verify', [
         'json' => [
            'code' => '123456',
            'identifier' => 'nonexistent@example.com',
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(401);
   }
}
