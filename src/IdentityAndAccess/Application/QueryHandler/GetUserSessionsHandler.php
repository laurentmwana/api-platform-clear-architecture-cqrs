<?php

namespace App\IdentityAndAccess\Application\QueryHandler;

use App\IdentityAndAccess\Application\Query\GetUserSessionsQuery;
use App\IdentityAndAccess\Domain\Entity\Session;
use App\IdentityAndAccess\Domain\Repository\SessionRepository;
use App\SharedContext\Application\Bus\Query\QueryHandler;

class GetUserSessionsHandler implements QueryHandler
{
   public function __construct(
      private SessionRepository $session
   ) {}

   /**
    * @param GetUserSessionsQuery $query
    * @return Session[]
    */
   public function __invoke(GetUserSessionsQuery $query): array
   {
      $sessions = $this->session->findAllByUserId($query->getUserId());

      return $sessions;
   }
}
