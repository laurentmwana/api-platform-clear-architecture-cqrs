<?php

namespace App\IdentityAndAccess\Presentation\Contraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class PasswordConfirmation extends Constraint
{
   public string $message = 'Password confirmation does not match.';

   /**
    * @var string[]
    */
   public array $fields = ['password', 'password_confirmation'];

   /**
    * @param string[] $fields
    * @param string[]|null $groups
    * @param mixed $payload
    */
   public function __construct(
      array $fields = [],
      ?array $groups = null,
      mixed $payload = null,
   ) {
      $this->fields = $fields;

      parent::__construct([], $groups, $payload);
   }

   public function getTargets(): string
   {
      return self::CLASS_CONSTRAINT;
   }
}
