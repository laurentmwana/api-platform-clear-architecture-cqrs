<?php

namespace App\Tests\ApiTestCase\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor;

use App\Tests\ApiTestCase\AbstractApiTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class AuthProcessorTest extends AbstractApiTestCase
{
   use ResetDatabase, Factories;

   private const DEFAULT_PASSWORD = 'password';

   protected function setUp(): void
   {
      parent::setUp();
   }

   public function testLoginSuccessWithEmail(): void
   {
      $email = 'test@example.com';

      $this->createUser($email, '+243820110123');

      $response = static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifier' => $email,
            'password' => self::DEFAULT_PASSWORD,
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseIsSuccessful();
      $this->assertResponseStatusCodeSame(200);

      $data = $response->toArray();
      $this->assertArrayHasKey('access_token', $data);
      $this->assertArrayHasKey('refresh_token', $data);
      $this->assertNotEmpty($data['access_token']);
      $this->assertNotEmpty($data['refresh_token']);
   }

   public function testLoginSuccessWithPhone(): void
   {
      $phone = '+243820110123';

      $this->createUser('test@example.com', $phone);

      $response = static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifier' => $phone,
            'password' => self::DEFAULT_PASSWORD,
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseIsSuccessful();
      $this->assertResponseStatusCodeSame(200);

      $data = $response->toArray();
      $this->assertArrayHasKey('access_token', $data);
      $this->assertNotEmpty($data['access_token']);
   }

   public function testLoginWithPhoneWithoutPlusShouldFail(): void
   {
      $phone = '+243820110123';

      $this->createUser('test@example.com', $phone);

      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifier' => '243820110123',
            'password' => self::DEFAULT_PASSWORD,
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(200);
   }

   public function testLoginWithPhoneLocalFormatShouldFail(): void
   {
      $phone = '+243820110123';

      $this->createUser('test@example.com', $phone);

      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifier' => '0820110123',
            'password' => self::DEFAULT_PASSWORD,
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(422);
   }

   public function testLoginFailureWrongPassword(): void
   {
      $email = 'test2@example.com';

      $this->createUser($email, '+243820110124');

      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifier' => $email,
            'password' => 'wrongpassword',
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(401);
   }

   public function testLoginFailureWithPhoneWrongPassword(): void
   {
      $this->createUser('test3@example.com', '+243800110125');

      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifier' => '+243800110120',
            'password' => self::DEFAULT_PASSWORD,
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(401);
   }

   public function testLoginFailureUserNotFoundByEmail(): void
   {
      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifier' => 'nonexistent@example.com',
            'password' => self::DEFAULT_PASSWORD,
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(401);
   }

   public function testLoginFailureUserNotFoundByPhone(): void
   {
      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifier' => '+243999999999',
            'password' => 'password123456',
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(401);
   }

   public function testLoginMissingIdentifier(): void
   {
      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'password' => 'password123456',
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(422);
   }

   public function testLoginMissingPassword(): void
   {
      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifier' => 'test@example.com',
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(422);
   }

   public function testLoginWithInvalidPhoneFormat(): void
   {
      $this->createUser('test4@example.com', '+243820110126');

      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifier' => '123',
            'password' => self::DEFAULT_PASSWORD,
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(422);
   }

   public function testLoginWithInvalidEmailFormat(): void
   {
      $this->createUser('test5@example.com', '+243820110127');

      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifier' => 'invalid-email',
            'password' => 'password123456',
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(422);
   }

   public function testLoginWithWrongPhonePrefix(): void
   {
      $this->createUser('test6@example.com', '+243820110128');

      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifier' => '+123456789012',
            'password' => 'password123456',
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(422);
   }

   public function testLogoutSuccess(): void
   {
      $email = 'logout@example.com';
      $this->createUser($email, '+243820110999');

      $token = $this->getToken([
         'identifier' => $email,
         'password'  => self::DEFAULT_PASSWORD,
      ]);

      $logoutResponse = static::createClient()
         ->request('POST', '/api/auth/logout', [
            'json' => [],
            'headers' => [
               ...$this->getHeadersContentJson(),
               'Authorization' => "Bearer $token",
            ],
         ]);

      $this->assertResponseIsSuccessful();
      $this->assertResponseStatusCodeSame(200);

      $data = $logoutResponse->toArray();
      $this->assertArrayHasKey('message', $data);
   }

   public function testLogoutWithoutTokenShouldFail(): void
   {
      static::createClient()->request('POST', '/api/auth/logout', [
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(401);
   }

   public function testLogoutWithInvalidTokenShouldFail(): void
   {
      static::createClient()->request('POST', '/api/auth/logout', [
         'headers' => array_merge(
            $this->getHeadersContentJson(),
            ['Authorization' => 'Bearer invalid.token.here']
         ),
      ]);

      $this->assertResponseStatusCodeSame(401);
   }

   public function testRefreshTokenSuccess(): void
   {
      $email = 'refresh@example.com';
      $this->createUser($email, '+243820110777');

      $loginResponse = static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifier' => $email,
            'password' => self::DEFAULT_PASSWORD,
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $loginData = $loginResponse->toArray();
      $this->assertArrayHasKey('refresh_token', $loginData);
      $refreshToken = $loginData['refresh_token'];

      $refreshResponse = static::createClient()->request('POST', '/api/auth/refresh-token', [
         'json' => [
            'refresh_token' => $refreshToken,
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseIsSuccessful();
      $this->assertResponseStatusCodeSame(200);

      $refreshData = $refreshResponse->toArray();
      $this->assertArrayHasKey('token', $refreshData);
      $this->assertArrayHasKey('refresh_token', $refreshData);
      $this->assertNotEmpty($refreshData['token']);
      $this->assertNotEmpty($refreshData['refresh_token']);
   }

   public function testRefreshTokenWithInvalidTokenShouldFail(): void
   {
      static::createClient()->request('POST', '/api/auth/refresh-token', [
         'json' => [
            'refresh_token' => 'nonexistent_or_expired_refresh_token_string',
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(401);
   }

   public function testDeleteSessionByIdSuccess(): void
   {
      $email = 'session.delete@example.com';

      $this->createUser($email, '+243820110555');

      $token = $this->getToken([
         'identifier' => $email,
         'password' => self::DEFAULT_PASSWORD,
      ]);

      $sessionsResponse = static::createClient()->request('GET', '/api/auth/sessions', [
         'headers' => [
            ...$this->getHeadersContentJson(),
            'Authorization' => "Bearer $token",
         ],
      ]);

      $this->assertResponseIsSuccessful();

      $sessions = $sessionsResponse->toArray();
      $this->assertNotEmpty($sessions, 'User should have at least one session');

      $sessionId = $sessions[0]['id'];

      $deleteResponse = static::createClient()->request('DELETE', "/api/auth/sessions/{$sessionId}", [
         'json' => [
            'current_password' => self::DEFAULT_PASSWORD,
         ],
         'headers' => [
            ...$this->getHeadersContentJson(),
            'Authorization' => "Bearer $token",
         ],
      ]);

      $this->assertResponseIsSuccessful();
      $this->assertResponseStatusCodeSame(204);
   }
}
