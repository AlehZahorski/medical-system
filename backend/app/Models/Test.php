<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $table = 'tests';

    protected $fillable = [
        'name',
        'value',
        'reference'
    ];

    protected $attributes = [
        'reference' => 'Brak specjalnych poleceń',
    ];

    protected static function booted(): void
    {
        static::creating(function (Test $test) {
            if (empty($test->reference)) {
                $test->reference = 'Brak specjalnych poleceń';
            }
        });
    }
}
