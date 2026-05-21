<?php

namespace App\IdentityAndAccess\Presentation\Contraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class PasswordConfirmationValidator extends ConstraintValidator
{
   /**
    * @param mixed $value
    * @param PasswordConfirmation $constraint
    * @return void
    */
   public function validate(mixed $value, Constraint $constraint): void
   {
      [$first, $second] = $constraint->fields;

      $firstGetter = 'get' . ucfirst($first);
      $secondGetter = 'get' . ucfirst($second);

      if (
         !method_exists($value, $firstGetter) ||
         !method_exists($value, $secondGetter)
      ) {
         return;
      }

      if (
         $value->$firstGetter() !==
         $value->$secondGetter()
      ) {
         $this->context
            ->buildViolation($constraint->message)
            ->atPath($second)
            ->addViolation();
      }
   }
}
