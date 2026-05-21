<?php

namespace App\IdentityAndAccess\Infrastructure\ApiPlatform\Resources;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\OpenApi\Model\MediaType;
use ApiPlatform\OpenApi\Model\Operation as OpenApiOperation;
use ApiPlatform\OpenApi\Model\Response;
use App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Provider\SessionsProvider;
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
                                 'ipAddress' => ['type' => 'string', 'nullable' => true],
                                 'userAgent' => ['type' => 'string', 'nullable' => true],
                                 'createdAt' => ['type' => 'string', 'format' => 'date-time'],
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
      )
   ]
)]
final class SessionResource
{
   private string $id;
   private string $userId;
   private ?string $userAgent = null;
   private ?string $ipAddress = null;
   private ?string $createdAt = null;

   public function getCreatedAt(): ?string
   {
      return $this->createdAt;
   }

   public function setCreatedAt(string $createdAt): static
   {
      $this->createdAt = $createdAt;

      return $this;
   }

   public function getUserAgent(): ?string
   {
      return $this->userAgent;
   }


   public function setUserAgent(?string $userAgent): static
   {
      $this->userAgent = $userAgent;

      return $this;
   }

   public function getIpAddress(): ?string
   {
      return $this->ipAddress;
   }


   public function setIpAddress(?string $ipAddress): static
   {
      $this->ipAddress = $ipAddress;

      return $this;
   }


   public function getUserId(): string
   {
      return $this->userId;
   }


   public function setUserId(string $userId): static
   {
      $this->userId = $userId;

      return $this;
   }

   public function getId(): string
   {
      return $this->id;
   }

   public function setId(string $id): static
   {
      $this->id = $id;

      return $this;
   }
}
