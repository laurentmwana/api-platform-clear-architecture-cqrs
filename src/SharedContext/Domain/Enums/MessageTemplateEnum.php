<?php

namespace App\SharedContext\Domain\Enums;

enum MessageTemplateEnum: string
{
   case MAGIC_LOGIN_EMAIL = 'messages/auth/email/magic_login.html.twig';
   case PASSWORD_RESET_EMAIL = 'messages/auth/email/password_reset.html.twig';
   case VERIFY_EMAIL = 'messages/auth/email/verify_email.html.twig';
   case VERIFY_PHONE_EMAIL = 'messages/auth/email/verify_phone.html.twig';
}
