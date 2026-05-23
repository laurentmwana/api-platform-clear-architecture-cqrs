<?php

namespace App\IdentityAndAccess\Infrastructure\ApiPlatform\Resources;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\MediaType;
use ApiPlatform\OpenApi\Model\Operation as OpenApiOperation;
use ApiPlatform\OpenApi\Model\RequestBody;
use ApiPlatform\OpenApi\Model\Response;
use App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor\MagicLoginProcessor;
use App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor\VerifyMagicLoginProcessor;
use App\IdentityAndAccess\Presentation\Input\IdentifierInput;
use App\IdentityAndAccess\Presentation\Input\VerifyOtpInput;
use App\IdentityAndAccess\Presentation\Output\JwtTokenOutput;
use App\IdentityAndAccess\Presentation\Output\SendOtpCodeOutput;
use ArrayObject;

#[ApiResource(
   shortName: 'IdentityAndAccess',
   description: 'Features Auth',
   operations: [
      new Post(
         uriTemplate: '/auth/magic-login',
         name: 'auth_magic_login_send',
         input: IdentifierInput::class,
         output: SendOtpCodeOutput::class,
         processor: MagicLoginProcessor::class,
         read: false,
         security: "is_granted('PUBLIC_ACCESS')",
         status: 200,
         openapi: new OpenApiOperation(
            summary: 'Send magic login code',
            description: 'Send a one-time password to the user\'s email or phone for magic login',
            requestBody: new RequestBody(
               content: new ArrayObject([
                  'application/json' => new MediaType(
                     new ArrayObject([
                        'type' => 'object',
                        'properties' => [
                           'identifier' => ['type' => 'string', 'description' => 'Email or phone number'],
                        ],
                        'required' => ['identifier']
                     ])
                  )
               ])
            ),
            responses: [
               '200' => new Response(
                  description: 'Magic login code sent successfully',
                  content: new ArrayObject([
                     'application/json' => new MediaType(
                        new ArrayObject([
                           'type' => 'object',
                           'properties' => [
                              'message' => ['type' => 'string'],
                              'expires_minutes' => ['type' => 'integer'],
                              'attempts' => ['type' => 'integer']
                           ]
                        ])
                     )
                  ])
               ),
               '400' => new Response(description: 'Bad request - Validation error'),
               '422' => new Response(description: 'Unprocessable entity - Invalid identifier'),
            ]
         ),
      ),
      new Post(
         uriTemplate: '/auth/magic-login/verify',
         name: 'auth_magic_login_verify',
         input: VerifyOtpInput::class,
         output: JwtTokenOutput::class,
         processor: VerifyMagicLoginProcessor::class,
         read: false,
         security: "is_granted('PUBLIC_ACCESS')",
         status: 200,
         openapi: new OpenApiOperation(
            summary: 'Verify magic login code',
            description: 'Verify the one-time password and authenticate the user',
            requestBody: new RequestBody(
               content: new ArrayObject([
                  'application/json' => new MediaType(
                     new ArrayObject([
                        'type' => 'object',
                        'properties' => [
                           'code' => ['type' => 'string', 'description' => 'One-time password code'],
                        ],
                        'required' => ['code', 'identifier']
                     ])
                  )
               ])
            ),
            responses: [
               '200' => new Response(
                  description: 'Authentication successful',
                  content: new ArrayObject([
                     'application/json' => new MediaType(
                        new ArrayObject([
                           'type' => 'object',
                           'properties' => [
                              'access_token' => ['type' => 'string'],
                              'refresh_token' => ['type' => 'string']
                           ]
                        ])
                     )
                  ])
               ),
               '400' => new Response(description: 'Bad request - Validation error'),
               '401' => new Response(description: 'Invalid or expired code'),
               '422' => new Response(description: 'Unprocessable entity - Too many attempts'),
            ]
         ),
      ),
   ]
)]
final class MagicLoginResource {}
