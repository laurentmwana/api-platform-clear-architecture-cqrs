<?php

namespace App\Tests\ApiTestCase\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor;

use App\IdentityAndAccess\Domain\ValueObject\DeliveryChannel;
use App\IdentityAndAccess\Domain\ValueObject\OtpType;
use App\Tests\ApiTestCase\AbstractApiTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class ResetPasswordProcessorTest extends AbstractApiTestCase
{
   use ResetDatabase, Factories;

   protected function setUp(): void
   {
      parent::setUp();
   }

   public function testResetPasswordSuccess(): void
   {
      $email = 'test@example.com';

      $user = $this->createUser(
         $email,
         '+243820110123',
      );

      $otp = $this->createOtp(
         $user,
         OtpType::passwordReset(),
         DeliveryChannel::email(),
      );

      $response = static::createClient()->request('POST', '/api/auth/reset-password', [
         'json' => [
            'identifier' => $email,
            'otp_code' => $otp->getCode()->value(),
            'new_password' => 'new-password',
            'password_confirmation' => 'new-password',
         ],
         'headers' => $this->getHeadersAccept(),
      ]);

      $this->assertResponseStatusCodeSame(200);

      $data = $response->toArray();

      $this->assertArrayHasKey('message', $data);
      $this->assertNotEmpty($data['message']);
   }

   public function testResetPasswordFailsWithInvalidOtp(): void
   {
      $email = 'test@example.com';

      $this->createUser(
         $email,
         '+243820110123',
      );

      static::createClient()->request('POST', '/api/auth/reset-password', [
         'json' => [
            'identifier' => $email,
            'otp_code' => '000000',
            'new_password' => 'new-password',
            'password_confirmation' => 'new-password',
         ],
         'headers' => $this->getHeadersAccept(),
      ]);

      $this->assertResponseStatusCodeSame(422);
   }

   public function testResetPasswordFailsWithPasswordMismatch(): void
   {
      $email = 'test@example.com';

      $user = $this->createUser(
         $email,
         '+243820110123',
      );

      $otp = $this->createOtp(
         $user,
         OtpType::passwordReset(),
         DeliveryChannel::email(),
      );

      static::createClient()->request('POST', '/api/auth/reset-password', [
         'json' => [
            'identifier' => $email,
            'otp_code' => $otp->getCode()->value(),
            'new_password' => 'password-1',
            'password_confirmation' => 'password-2',
         ],
         'headers' => $this->getHeadersAccept(),
      ]);

      $this->assertResponseStatusCodeSame(422);
   }

   public function testResetPasswordFailsWhenNotAuthenticated(): void
   {
      static::createClient()->request('POST', '/api/auth/reset-password', [
         'json' => [
            'identifier' => 'test@example.com',
            'otp_code' => '123456',
            'new_password' => 'new-password',
            'password_confirmation' => 'new-password',
         ],
         'headers' => $this->getHeadersAccept(),
      ]);

      $this->assertResponseStatusCodeSame(422);
   }
}
