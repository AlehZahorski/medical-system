<?php

namespace App\Exceptions\Imports;

use Exception;

class InvalidSexException extends Exception
{
    public function __construct(string $message = 'Nieprawidłowa płeć.')
    {
        parent::__construct($message);
    }
}
