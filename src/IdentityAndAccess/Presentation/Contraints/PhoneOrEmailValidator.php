<?php

namespace App\IdentityAndAccess\Presentation\Contraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PhoneOrEmailValidator extends ConstraintValidator
{
   /**
    * @param string|null $value
    * @param PhoneOrEmail $constraint
    */
   public function validate($value, Constraint $constraint): void
   {
      if (null === $value || '' === $value) {
         return;
      }

      if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
         return;
      }

      $cleanedPhone = (string) preg_replace('/[\s\-]/', '', $value);

      if (!str_starts_with($cleanedPhone, '+')) {
         if (preg_match('/^243[0-9]{9}$/', $cleanedPhone)) {
            $cleanedPhone = '+' . $cleanedPhone;
         }
      }

      if (preg_match('/^\+\d{8,15}$/', $cleanedPhone)) {
         return;
      }

      $this->context->buildViolation($constraint->message)
         ->setParameter('{{ value }}', $this->formatValue($value))
         ->addViolation();
   }
}
