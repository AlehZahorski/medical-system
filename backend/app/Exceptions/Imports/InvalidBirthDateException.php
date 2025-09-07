<?php

namespace App\Exceptions\Imports;

use Exception;

class InvalidBirthDateException extends Exception
{
    public function __construct(string $message = 'Nieprawidłowy format daty.')
    {
        parent::__construct($message);
    }
}
