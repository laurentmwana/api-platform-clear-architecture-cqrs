<?php

namespace App\IdentityAndAccess\Infrastructure\ApiPlatform\Resources;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\MediaType;
use ApiPlatform\OpenApi\Model\Operation as OpenApiOperation;
use ApiPlatform\OpenApi\Model\RequestBody;
use ApiPlatform\OpenApi\Model\Response;
use App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor\ForgotPasswordProcessor;
use App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor\ResetPasswordProcessor;
use App\IdentityAndAccess\Presentation\Input\IdentifierInput;
use App\IdentityAndAccess\Presentation\Input\ResetPasswordInput;
use App\IdentityAndAccess\Presentation\Output\ResetPasswordOutput;
use App\IdentityAndAccess\Presentation\Output\SendOtpCodeOutput;
use ArrayObject;

#[ApiResource(
   shortName: 'IdentityAndAccess',
   description: 'Forgot and reset password',
   operations: [
      new Post(
         uriTemplate: '/auth/forgot-password',
         name: 'auth_forgot_password',
         input: IdentifierInput::class,
         output: SendOtpCodeOutput::class,
         processor: ForgotPasswordProcessor::class,
         read: false,
         security: "is_granted('PUBLIC_ACCESS')",
         status: 200,
         openapi: new OpenApiOperation(
            summary: 'Forgot password',
            description: 'Send OTP code for password reset',
            requestBody: new RequestBody(
               content: new ArrayObject([
                  'application/json' => new MediaType(
                     new ArrayObject([
                        'type' => 'object',
                        'properties' => [
                           'identifier' => ['type' => 'string'],
                        ],
                        'required' => ['identifier'],
                     ])
                  )
               ])
            ),
            responses: [
               '200' => new Response(
                  description: 'OTP sent successfully',
                  content: new ArrayObject([
                     'application/json' => new MediaType(
                        new ArrayObject([
                           'type' => 'object',
                           'properties' => [
                              'message' => ['type' => 'string'],
                              'expires_minutes' => ['type' => 'integer'],
                              'attempts' => ['type' => 'integer'],
                           ],
                        ])
                     )
                  ])
               ),
               '400' => new Response(
                  description: 'Bad request - Validation error',
               ),
            ],
         ),
      ),

      new Post(
         uriTemplate: '/auth/reset-password',
         name: 'auth_reset_password',
         input: ResetPasswordInput::class,
         output: ResetPasswordOutput::class,
         processor: ResetPasswordProcessor::class,
         read: false,
         security: "is_granted('PUBLIC_ACCESS')",
         status: 200,
         openapi: new OpenApiOperation(
            summary: 'Reset password',
            description: 'Reset user password with OTP code',
            requestBody: new RequestBody(
               content: new ArrayObject([
                  'application/json' => new MediaType(
                     new ArrayObject([
                        'type' => 'object',
                        'properties' => [
                           'identifier' => ['type' => 'string'],
                           'otp_code' => ['type' => 'string'],
                           'new_password' => ['type' => 'string'],
                           'password_confirmation' => ['type' => 'string'],
                        ],
                        'required' => [
                           'identifier',
                           'otp_code',
                           'new_password',
                           'password_confirmation',
                        ],
                     ])
                  )
               ])
            ),
            responses: [
               '200' => new Response(
                  description: 'Password reset successfully',
                  content: new ArrayObject([
                     'application/json' => new MediaType(
                        new ArrayObject([
                           'type' => 'object',
                           'properties' => [
                              'message' => ['type' => 'string'],
                           ],
                        ])
                     )
                  ])
               ),
               '400' => new Response(
                  description: 'Bad request - Validation error',
               ),
               '422' => new Response(
                  description: 'Invalid OTP code',
               ),
            ],
         ),
      ),
   ]
)]
final class ResetPasswordResource {}
