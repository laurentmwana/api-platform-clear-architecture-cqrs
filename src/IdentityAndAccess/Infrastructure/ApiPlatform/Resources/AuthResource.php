<?php

namespace App\IdentityAndAccess\Infrastructure\ApiPlatform\Resources;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\MediaType;
use ApiPlatform\OpenApi\Model\Operation as OpenApiOperation;
use ApiPlatform\OpenApi\Model\Response;
use App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor\LoginProcessor;
use App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor\LogoutProcessor;
use App\IdentityAndAccess\Presentation\Input\LoginInput;
use App\IdentityAndAccess\Presentation\Output\JwtTokenOutput;
use App\IdentityAndAccess\Presentation\Output\LogoutOutput;
use ArrayObject;

#[ApiResource(
   shortName: 'IdentityAndAccess',
   description: 'Auth User',
   operations: [
      new Post(
         uriTemplate: '/auth/login',
         name: 'auth_login',
         input: LoginInput::class,
         output: JwtTokenOutput::class,
         processor: LoginProcessor::class,
         read: false,
         security: "is_granted('PUBLIC_ACCESS')",
         status: 200,
         openapi: new OpenApiOperation(
            summary: 'User authentication',
            description: 'Authenticate user with email or phone number and password',
            responses: [
               '200' => new Response(
                  description: 'Authentication successful',
                  content: new ArrayObject([
                     'application/json' => new MediaType(
                        new ArrayObject([
                           'type' => 'object',
                           'properties' => [
                              'token' => ['type' => 'string'],
                              'refresh_token' => ['type' => 'string'],
                           ],
                        ])
                     ),
                  ])
               ),
               '400' => new Response(description: 'Bad request'),
               '401' => new Response(description: 'Invalid credentials'),
            ]
         ),
      ),

      new Post(
         uriTemplate: '/auth/logout',
         name: 'auth_logout',
         output: LogoutOutput::class,
         processor: LogoutProcessor::class,
         read: false,
         security: "is_granted('ROLE_USER')",
         status: 200,
         openapi: new OpenApiOperation(
            summary: 'Logout user',
            description: 'Invalidate current user session/token',
            responses: [
               '200' => new Response(
                  description: 'Logout successful',
                  content: new ArrayObject([
                     'application/json' => new MediaType(
                        new ArrayObject([
                           'type' => 'object',
                           'properties' => [
                              'message' => [
                                 'type' => 'string',
                                 'example' => 'Logged out successfully',
                              ],
                           ],
                        ])
                     ),
                  ])
               ),
               '401' => new Response(description: 'Unauthorized'),
            ]
         ),
      ),
   ]
)]
final class AuthResource {}
