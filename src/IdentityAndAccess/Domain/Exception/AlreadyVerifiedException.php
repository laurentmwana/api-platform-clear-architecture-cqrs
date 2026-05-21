<?php

namespace App\IdentityAndAccess\Domain\Exception;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class AlreadyVerifiedException extends UnprocessableEntityHttpException {}
