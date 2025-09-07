<?php

namespace App\Models;

use App\Enums\PatientTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Patient extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    protected $primaryKey = 'patient_id';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $table = 'patients';

    protected $fillable = [
        'patient_id',
        'name',
        'surname',
        'login',
        'password',
        'sex',
        'birth'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'sex' => PatientTypeEnum::class,
        'birth' => 'date'
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'patient_id', 'patient_id');
    }

    protected static function booted(): void
    {
        static::creating(function (Patient $patient) {
            if (empty($patient->login)) {
                $patient->login = $patient->generateLoginByPersonalData();
            }

            if (empty($patient->password)) {
                $patient->password = $patient->generatePasswordByBirthDate();
            }

            $originalLogin = $patient->login;
            $i = 1;

            while (
            self::query()->where('login', $patient->login)->exists()
            ) {
                $patient->login = $originalLogin . $i;
                $i++;
            }
        });
    }

    public function generateLoginByPersonalData(): string
    {
        return Str::studly($this->name . $this->surname);
    }

    public function generatePasswordByBirthDate(): string
    {
        $birth = $this->birth instanceof Carbon
            ? $this->birth
            : Carbon::parse($this->birth);

        $plainPassword = $birth->format('Y-m-d');
        return Hash::make($plainPassword);
    }
}
