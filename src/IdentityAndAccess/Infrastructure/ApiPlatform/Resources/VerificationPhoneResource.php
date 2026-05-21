<?php

namespace App\IdentityAndAccess\Infrastructure\ApiPlatform\Resources;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\MediaType;
use ApiPlatform\OpenApi\Model\Operation as OpenApiOperation;
use ApiPlatform\OpenApi\Model\RequestBody;
use ApiPlatform\OpenApi\Model\Response;
use App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor\SendVerificationPhoneProcessor;
use App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor\VerifyPhoneProcessor;
use App\IdentityAndAccess\Presentation\Input\VerifyEmailOrPhoneInput;
use App\IdentityAndAccess\Presentation\Output\SendOtpCodeOutput;
use ArrayObject;

#[ApiResource(
   shortName: 'IdentityAndAccess',
   description: 'Phone verification management',
   operations: [
      new Post(
         uriTemplate: '/auth/phone/send-verification',
         name: 'auth_phone_send_verification',
         output: SendOtpCodeOutput::class,
         processor: SendVerificationPhoneProcessor::class,
         read: false,
         security: "is_granted('ROLE_USER')",
         status: 200,
         openapi: new OpenApiOperation(
            summary: 'Send phone verification code',
            description: 'Send a verification OTP code to the authenticated user phone number',
            requestBody: new RequestBody(
               content: new ArrayObject([
                  'application/json' => new MediaType(
                     new ArrayObject([
                        'type' => 'object',
                        'properties' => [],
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
                              'message' => [
                                 'type' => 'string',
                              ],
                              'expires_minutes' => [
                                 'type' => 'integer',
                              ],
                              'attempts' => [
                                 'type' => 'integer',
                              ],
                           ],
                        ])
                     ),
                  ])
               ),
               '401' => new Response(
                  description: 'Unauthorized'
               ),
               '422' => new Response(
                  description: 'Phone already verified'
               ),
            ]
         ),
      ),
      new Post(
         uriTemplate: '/auth/phone/verify',
         name: 'auth_phone_verify',
         input: VerifyEmailOrPhoneInput::class,
         output: SendOtpCodeOutput::class,
         processor: VerifyPhoneProcessor::class,
         read: false,
         security: "is_granted('ROLE_USER')",
         status: 200,
         openapi: new OpenApiOperation(
            summary: 'Verify phone with OTP code',
            description: 'Verify the user phone using the OTP code sent previously',
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
                  description: 'Phone verified successfully',
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
               '422' => new Response(description: 'Phone already verified')
            ]
         ),
      ),
   ]
)]
final class VerificationPhoneResource {}
