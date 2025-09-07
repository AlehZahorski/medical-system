<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Test;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $test = Test::factory()->create();

        return [
            'order_id' => Order::factory(),
            'item_type' => Test::class,
            'item_id' => $test->id,
        ];
    }
}
