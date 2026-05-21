<?php

namespace App\SharedContext\Domain\Enums;

enum MessageSubjectEnum: string
{
   case MAGIC_LOGIN = 'Your Magic Login Code';
   case PASSWORD_RESET = 'Password Reset Request';
   case VERIFY_EMAIL = 'Verify Your Email Address';
   case VERIFY_PHONE = 'Verify Your Phone Number';
}
