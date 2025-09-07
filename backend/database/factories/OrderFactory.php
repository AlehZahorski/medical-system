<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'order_id' => $this->faker->numberBetween(1000, 9999),
            'patient_id' => Patient::factory(),
        ];
    }
}
