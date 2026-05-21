<?php

namespace App\IdentityAndAccess\Infrastructure\ApiPlatform\Resources;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\MediaType;
use ApiPlatform\OpenApi\Model\Operation as OpenApiOperation;
use ApiPlatform\OpenApi\Model\RequestBody;
use ApiPlatform\OpenApi\Model\Response;

use App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor\SendVerificationEmailProcessor;
use App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor\VerifyEmailProcessor;

use App\IdentityAndAccess\Presentation\Input\VerifyEmailOrPhoneInput;
use App\IdentityAndAccess\Presentation\Output\SendOtpCodeOutput;

use ArrayObject;

#[ApiResource(
   shortName: 'IdentityAndAccess',
   description: 'Email verification management',
   operations: [
      new Post(
         uriTemplate: '/auth/email/send-verification',
         name: 'auth_email_send_verification',
         output: SendOtpCodeOutput::class,
         processor: SendVerificationEmailProcessor::class,
         read: false,
         security: "is_granted('ROLE_USER')",
         status: 200,
         openapi: new OpenApiOperation(
            summary: 'Send email verification code',
            description: 'Send a verification OTP code to the authenticated user email',
            requestBody: new RequestBody(
               content: new ArrayObject([
                  'application/json' => new MediaType(
                     new ArrayObject([
                        'type' => 'object',
                        'properties' => []
                     ])
                  )
               ])
            ),
            responses: [
               '200' => new Response(
                  description: 'Verification code sent successfully',
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
               '401' => new Response(description: 'Unauthorized'),
               '422' => new Response(description: 'Email already verified')
            ]
         ),
      ),
      new Post(
         uriTemplate: '/auth/email/verify',
         name: 'auth_email_verify',
         input: VerifyEmailOrPhoneInput::class,
         output: SendOtpCodeOutput::class,
         processor: VerifyEmailProcessor::class,
         read: false,
         security: "is_granted('ROLE_USER')",
         status: 200,
         openapi: new OpenApiOperation(
            summary: 'Verify email with OTP code',
            description: 'Verify the user email using the OTP code sent previously',
            requestBody: new RequestBody(
               content: new ArrayObject([
                  'application/json' => new MediaType(
                     new ArrayObject([
                        'type' => 'object',
                        'properties' => [
                           'otp_code' => ['type' => 'string']
                        ],
                        'required' => ['otp_code']
                     ])
                  )
               ])
            ),
            responses: [
               '200' => new Response(
                  description: 'Email verified successfully',
                  content: new ArrayObject([
                     'application/json' => new MediaType(
                        new ArrayObject([
                           'type' => 'object',
                           'properties' => [
                              'message' => ['type' => 'string']
                           ]
                        ])
                     )
                  ])
               ),
               '401' => new Response(description: 'Invalid or expired code'),
               '422' => new Response(description: 'Email already verified')
            ]
         ),
      ),
   ]
)]
final class VerificationEmailResource {}
