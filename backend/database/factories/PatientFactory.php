<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class PatientFactory extends Factory
{
    protected $model = Patient::class;

    public function definition(): array
    {
        $name = $this->faker->firstName();
        $surname = $this->faker->lastName();
        $birth = $this->faker->date('Y-m-d', '-20 years');

        return [
            'patient_id' => $this->faker->unique()->numberBetween(1000, 9999),
            'name' => $name,
            'surname' => $surname,
            'login' => Str::slug($name . $surname . rand(1, 999)),
            'password' => Hash::make('password'),
            'sex' => $this->faker->randomElement(['male', 'female']),
            'birth' => $birth,
        ];
    }
}
