<?php

namespace Tests\Feature\Api;

use App\Models\Patient;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_guest_cannot_access_order_list(): void
    {
        $response = $this->getJson('/api/results');

        $response->assertUnauthorized();
    }

    public function test_it_returns_empty_order_array_if_no_orders(): void
    {
        $patient = Patient::factory()->create();
        Sanctum::actingAs($patient);

        $response = $this->getJson('/api/results');

        $response->assertOk();

        $response->assertJsonFragment([
            'id' => $patient->patient_id,
        ]);

        $response->assertJson([
            'orders' => [],
        ]);
    }

    public function test_it_authenticated_user_can_get_order_list_with_patient_data(): void
    {
        // Arrange
        $patient = Patient::factory()->create();
        Sanctum::actingAs($patient);

        $test = Test::factory()->create([
            'name' => 'Morfologia',
            'value' => '13.5',
            'reference' => '12 - 16',
        ]);

        $order = Order::factory()->create([
            'patient_id' => $patient->patient_id
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->order_id,
            'item_type' => Test::class,
            'item_id' => $test->id,
        ]);

        // Action
        $response = $this->getJson('/api/results');

        // Assert
        $response->assertOk()
            ->assertJsonStructure([
                'patient' => [
                    'id',
                    'name',
                    'surname',
                    'sex',
                    'birthDate',
                ],
                'orders' => [
                    [
                        'orderId',
                        'results' => [
                            [
                                'name',
                                'value',
                                'reference',
                            ]
                        ]
                    ]
                ]
            ]);
    }
}
