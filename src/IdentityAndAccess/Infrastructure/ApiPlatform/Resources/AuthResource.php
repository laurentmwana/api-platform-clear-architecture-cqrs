<?php

namespace App\IdentityAndAccess\Infrastructure\ApiPlatform\Resources;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\MediaType;
use ApiPlatform\OpenApi\Model\Operation as OpenApiOperation;
use ApiPlatform\OpenApi\Model\Response;
use ApiPlatform\OpenApi\Model\RequestBody;
use App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor\LoginProcessor;
use App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor\LogoutProcessor;
use App\IdentityAndAccess\Presentation\Input\LoginInput;
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
                              'access_token' => ['type' => 'string'],
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
         uriTemplate: '/auth/refresh-token',
         name: 'api_refresh_token',
         read: false,
         deserialize: false,
         validate: false,
         processor: LoginProcessor::class,
         openapi: new OpenApiOperation(
            summary: 'Refresh JWT Token',
            description: 'Get a new access token using a valid refresh token',
            requestBody: new RequestBody(
               description: 'The refresh token issued at login',
               content: new ArrayObject([
                  'application/json' => new MediaType(
                     new ArrayObject([
                        'type' => 'object',
                        'properties' => [
                           'refresh_token' => [
                              'type' => 'string',
                              'example' => 'string'
                           ],
                        ],
                        'required' => ['refresh_token']
                     ])
                  )
               ])
            ),
            responses: [
               '200' => new Response(
                  description: 'Tokens rotated successfully',
                  content: new ArrayObject([
                     'application/json' => new MediaType(
                        new ArrayObject([
                           'type' => 'object',
                           'properties' => [
                              'access_token' => ['type' => 'string', 'description' => 'The new access token'],
                              'refresh_token' => ['type' => 'string', 'description' => 'The next refresh token to store']
                           ],
                        ])
                     ),
                  ])
               ),
               '401' => new Response(description: 'Invalid or expired refresh token'),
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
