<?php

namespace App\IdentityAndAccess\Infrastructure\ApiPlatform\Resources;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\OpenApi\Model\MediaType;
use ApiPlatform\OpenApi\Model\Operation as OpenApiOperation;
use ApiPlatform\OpenApi\Model\Response;
use ApiPlatform\OpenApi\Model\Schema;
use App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor\DeleteSessionProcessor;
use App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Provider\SessionsProvider;
use App\SharedContext\Presentation\Input\CurrentPasswordInput;
use App\SharedContext\Presentation\Output\MessageOutput;
use ArrayObject;

#[ApiResource(
   shortName: 'IdentityAndAccess',
   description: 'User Sessions',
   operations: [

      new GetCollection(
         uriTemplate: '/auth/sessions',
         name: 'auth_sessions_index',
         provider: SessionsProvider::class,
         security: "is_granted('ROLE_USER')",
         openapi: new OpenApiOperation(
            summary: 'Get user sessions',
            description: 'Retrieve all active sessions for the authenticated user',
            responses: [
               '200' => new Response(
                  description: 'List of sessions',
                  content: new ArrayObject([
                     'application/json' => new MediaType(
                        new ArrayObject([
                           'type' => 'array',
                           'items' => [
                              'type' => 'object',
                              'properties' => [
                                 'id' => ['type' => 'string'],
                                 'ip_address' => ['type' => 'string', 'nullable' => true],
                                 'user_agent' => ['type' => 'string', 'nullable' => true],
                                 'created_at' => ['type' => 'string', 'format' => 'date-time'],
                              ],
                           ],
                        ])
                     )
                  ])
               ),
               '401' => new Response(
                  description: 'Unauthorized'
               ),
            ]
         )
      ),

      new Delete(
         uriTemplate: '/auth/sessions/{id}',
         name: 'auth_sessions_delete',
         processor: DeleteSessionProcessor::class,
         input: CurrentPasswordInput::class,
         output: MessageOutput::class,
         read: false,
         security: "is_granted('ROLE_USER')",
         deserialize: true,
         openapi: new OpenApiOperation(
            summary: 'Delete a user session',
            description: 'Delete a specific session. Requires current_password in request body.',

            requestBody: new \ApiPlatform\OpenApi\Model\RequestBody(
               required: true,
               content: new ArrayObject([
                  'application/json' => new MediaType(
                     new ArrayObject([
                        'type' => 'object',
                        'properties' => [
                           'current_password' => [
                              'type' => 'string',
                              'example' => 'secret'
                           ],
                        ],
                        'required' => ['current_password']
                     ])
                  )
               ])
            ),

            responses: [
               '204' => new Response(
                  description: 'Session deleted successfully'
               ),
               '401' => new Response(
                  description: 'Unauthorized'
               ),
               '404' => new Response(
                  description: 'Session not found'
               ),
            ]
         )
      ),
   ]
)]
final class SessionResource {}
