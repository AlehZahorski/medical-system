<?php

namespace App\Enums;

enum PatientTypeEnum: string
{
    case MALE = 'male';

    case FEMALE = 'female';

    public function label(): string
    {
        return match ($this) {
            self::MALE => 'Mężczyzna',
            self::FEMALE => 'Kobieta',
        };
    }
}
