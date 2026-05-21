<?php

namespace App\Tests\ApiTestCase\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor;

use App\IdentityAndAccess\Domain\ValueObject\DeliveryChannel;
use App\IdentityAndAccess\Domain\ValueObject\OtpType;
use App\Tests\ApiTestCase\AbstractApiTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class VerificationEmailProcessorTest extends AbstractApiTestCase
{
   use ResetDatabase, Factories;

   protected function setUp(): void
   {
      parent::setUp();
   }

   public function testSendVerificationSuccess(): void
   {
      $email = 'test@example.com';

      $this->createUser(
         $email,
         '+243820110123',
         emailUnverified: true,
      );

      $token = $this->getToken([
         'identifier' => $email,
         'password' => 'password',
      ]);

      $response = static::createClient()->request('POST', '/api/auth/email/send-verification', [
         'json' => [],
         'headers' => [
            ...$this->getHeadersAccept(),
            'Authorization' => "Bearer $token",
         ],
      ]);

      $this->assertResponseStatusCodeSame(200);

      $data = $response->toArray();

      $this->assertArrayHasKey('message', $data);
      $this->assertArrayHasKey('expires_minutes', $data);
      $this->assertArrayHasKey('attempts', $data);
   }

   public function testSendVerificationWhenAlreadyVerified(): void
   {
      $email = 'test@example.com';

      $this->createUser(
         $email,
         '+243820110123',
         emailUnverified: false
      );

      $token = $this->getToken([
         'identifier' => $email,
         'password' => 'password',
      ]);

      $response = static::createClient()->request('POST', '/api/auth/email/send-verification', [
         'json' => [],
         'headers' => [
            ...$this->getHeadersAccept(),
            'Authorization' => "Bearer $token",
         ],
      ]);

      $this->assertResponseStatusCodeSame(422);
   }

   public function testVerifyEmailSuccess(): void
   {
      $email = 'test@example.com';

      $user = $this->createUser(
         $email,
         emailUnverified: true,
      );

      $token = $this->getToken([
         'identifier' => $email,
         'password' => 'password',
      ]);

      $otp = $this->createOtp(
         $user,
         OtpType::verifyEmail(),
         DeliveryChannel::email(),
      );

      $response = static::createClient()->request('POST', '/api/auth/email/verify', [
         'json' => [
            'otp_code' => $otp->getCode()->value(),
         ],
         'headers' => [
            ...$this->getHeadersAccept(),
            'Authorization' => "Bearer $token",
         ],
      ]);

      $this->assertResponseStatusCodeSame(200);

      $data = $response->toArray();

      $this->assertArrayHasKey('message', $data);
      $this->assertNotEmpty($data['message']);
   }

   public function testVerifyEmailFailsWithInvalidOtp(): void
   {
      $email = 'test@example.com';

      $user = $this->createUser(
         $email,
         '+243820110123',
         emailUnverified: false,
      );

      $token = $this->getToken([
         'identifier' => $email,
         'password' => 'password',
      ]);


      $this->createOtp(
         $user,
         OtpType::verifyEmail(),
         DeliveryChannel::email(),
      );

      static::createClient()->request('POST', '/api/auth/email/verify', [
         'json' => [
            'otp_code' => '000000',
         ],
         'headers' => [
            ...$this->getHeadersAccept(),
            'Authorization' => "Bearer $token",
         ],
      ]);

      $this->assertResponseStatusCodeSame(422);
   }

   public function testVerifyEmailFailsWithEmptyOtp(): void
   {
      $email = 'test@example.com';

      $this->createUser(
         $email,
         '+243820110123',
         emailUnverified: false,
      );

      $token = $this->getToken([
         'identifier' => $email,
         'password' => 'password',
      ]);

      static::createClient()->request('POST', '/api/auth/email/verify', [
         'json' => [
            'otp_code' => '',
         ],
         'headers' => [
            ...$this->getHeadersAccept(),
            'Authorization' => "Bearer $token",
         ],
      ]);

      $this->assertResponseStatusCodeSame(422);
   }

   public function testVerifyEmailFailsWhenNotAuthenticated(): void
   {
      static::createClient()->request('POST', '/api/auth/email/verify', [
         'json' => [
            'otp_code' => '123456',
         ],
         'headers' => $this->getHeadersAccept(),
      ]);

      $this->assertResponseStatusCodeSame(401);
   }

   public function testVerifyEmailFailsWithInvalidToken(): void
   {
      static::createClient()->request('POST', '/api/auth/email/verify', [
         'json' => [
            'otp_code' => '123456',
         ],
         'headers' => [
            ...$this->getHeadersAccept(),
            'Authorization' => 'Bearer invalid-token',
         ],
      ]);

      $this->assertResponseStatusCodeSame(401);
   }
}
