<?php

namespace App\Tests\ApiTestCase\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor;

use App\IdentityAndAccess\Domain\ValueObject\DeliveryChannel;
use App\IdentityAndAccess\Domain\ValueObject\OtpType;
use App\Tests\ApiTestCase\AbstractApiTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class VerificationPhoneProcessorTest extends AbstractApiTestCase
{
   use ResetDatabase, Factories;

   protected function setUp(): void
   {
      parent::setUp();
   }

   public function testSendVerificationSuccess(): void
   {
      $phone = '+243820110123';

      $this->createUser(
         'test@example.com',
         $phone,
         phoneUnverified: true
      );

      $token = $this->getToken([
         'identifier' => $phone,
         'password' => 'password',
      ]);

      $response = static::createClient()->request('POST', '/api/auth/phone/send-verification', [
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

      $this->assertNotEmpty($data['message']);
      $this->assertNotEmpty($data['expires_minutes']);
      $this->assertNotEmpty($data['attempts']);
   }

   public function testSendVerificationWhenPhoneAlreadyVerified(): void
   {
      $phone = '+243820110123';

      $this->createUser(
         'test@example.com',
         $phone,
         phoneUnverified: false
      );

      $token = $this->getToken([
         'identifier' => $phone,
         'password' => 'password',
      ]);

      static::createClient()->request('POST', '/api/auth/phone/send-verification', [
         'json' => [],
         'headers' => [
            ...$this->getHeadersAccept(),
            'Authorization' => "Bearer $token",
         ],
      ]);

      $this->assertResponseStatusCodeSame(422);
   }

   public function testVerifyPhoneSuccess(): void
   {
      $phone = '+243820110123';

      $user = $this->createUser(
         'test@example.com',
         $phone,
         phoneUnverified: true
      );

      $token = $this->getToken([
         'identifier' => $phone,
         'password' => 'password',
      ]);

      $otp = $this->createOtp(
         $user,
         OtpType::verifyPhone(),
         DeliveryChannel::sms(),
      );

      $response = static::createClient()->request('POST', '/api/auth/phone/verify', [
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

   public function testVerifyPhoneFailsWithInvalidOtp(): void
   {
      $phone = '+243820110123';

      $user = $this->createUser(
         'test@example.com',
         $phone,
         phoneUnverified: true
      );

      $token = $this->getToken([
         'identifier' => $phone,
         'password' => 'password',
      ]);

      $this->createOtp(
         $user,
         OtpType::verifyPhone(),
         DeliveryChannel::sms(),
      );

      static::createClient()->request('POST', '/api/auth/phone/verify', [
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

   public function testVerifyPhoneFailsWithEmptyOtp(): void
   {
      $phone = '+243820110123';

      $this->createUser(
         'test@example.com',
         $phone,
         phoneUnverified: true
      );

      $token = $this->getToken([
         'identifier' => $phone,
         'password' => 'password',
      ]);

      static::createClient()->request('POST', '/api/auth/phone/verify', [
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

   public function testVerifyPhoneFailsWhenNotAuthenticated(): void
   {
      static::createClient()->request('POST', '/api/auth/phone/verify', [
         'json' => [
            'otp_code' => '123456',
         ],
         'headers' => $this->getHeadersAccept(),
      ]);

      $this->assertResponseStatusCodeSame(401);
   }

   public function testVerifyPhoneFailsWithInvalidToken(): void
   {
      static::createClient()->request('POST', '/api/auth/phone/verify', [
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
