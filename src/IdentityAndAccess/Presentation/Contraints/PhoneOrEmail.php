<?php

namespace App\IdentityAndAccess\Presentation\Contraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class PhoneOrEmail extends Constraint
{
   public string $message = 'Please enter a valid email address or a valid international phone number (e.g. +243...).';

   public function __construct(
      public string $mode = 'strict',
      ?array $groups = null,
      mixed $payload = null,
   ) {
      parent::__construct([], $groups, $payload);
   }
}
