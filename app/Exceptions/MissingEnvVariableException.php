<?php

namespace App\Exceptions;

use RuntimeException;

class MissingEnvVariableException extends RuntimeException
{
    public function __construct(string $message = 'Environment variables have not been specified.')
    {
        parent::__construct($message);
    }
}
